<?php

abstract class gm_base_controller {

	/**
	 * 検索パラメータの接頭詞を指定
	 *
	 * @var string
	 */
	public $query_prefix = "s_";

	/**
	 * シングルトンインスタンス保存用プロパティ
	 * @var array
	 */
	private static $instance = [];

	/**
	 * ソートに利用するオプションを取得
	 *
	 * @var array
	 */
	public $sort_options = [];

	/**
	 * 利用する検索クエリパラメータを保存
	 * @var array
	 */
	public $default_search_query_vars = [];

	/**
	 * 検索で利用するタクソノミーを取得
	 * @var array
	 */
	public $taxonomies = [];

	/**
	 * 表示件数を指定する際の選択肢
	 * @var int[]
	 */
	public $per_page_options = [
		10,
		20,
		50,
		100,
	];

	/**
	 * WP_Queryインスタンスを保存
	 * @var null
	 */
	public $query = null;

	/**
	 * 現状の検索パラメータを保存
	 * @var array
	 */
	public $current_search_query_vars = [];

	/**
	 * 初期化
	 *
	 * @param null $query
	 *
	 * @throws ErrorException
	 */
	protected function __construct( $query = null ) {
		if ( isset( self::$instance[ get_called_class() ] ) ) {
			throw new ErrorException( "error" );
		}
		static::initialize();
	}

	/**
	 * シングルトンクラス
	 * pre_get_posts フィルターが多重でかからないように一回のみ初期化可能に
	 *
	 * @param null $query
	 *
	 * @return mixed|static
	 * @throws ErrorException
	 */
	public static function get_instance( $query = null ) {
		$class = get_called_class();
		if ( ! isset( self::$instance[ $class ] ) ) {
			self::$instance[ $class ] = new static( $query );
		}

		return self::$instance[ $class ];
	}

	/**
	 * WP_Query オブジェクトをセットする
	 *
	 * @param null $query
	 */
	public function set_query( $query = null ) {
		if ( $query === null ) {
			global $wp_query;
			$this->query = $wp_query;
		} else {
			$query->get_posts();
			$this->query = $query;
		}
	}

	/**
	 * 初期化用メソッド
	 */
	protected function initialize() {
		$this->set_query();
		$this->set_default_search_query_vars();
		$this->set_sort_options();
		$this->set_current_search_query_vars();
		$this->add_hooks();
	}

	/**
	 * pre_get_posts フィルターに登録
	 */
	public function add_hooks() {
		add_filter( "pre_get_posts", [ $this, "filter_query" ], 10, 1 );
	}

	/**
	 * タクソノミーを検索クエリーに追加する
	 */
	public function set_default_search_query_vars() {
		foreach ( $this->taxonomies as $taxonomies ) {
			$key                                     = static::get_search_param_name( $taxonomies );
			$this->default_search_query_vars[ $key ] = "";
		}

		return $this;
	}

	/**
	 * 現在の検索パラメータをセットする
	 */
	public function set_current_search_query_vars() {
		foreach ( $this->default_search_query_vars as $key => $value ) {
			$this->current_search_query_vars[ $key ] = $this->get_param( $key );
		}
	}


	/**
	 * $_GETから値を取得
	 * 無害化を実行
	 *
	 * @param $key
	 *
	 * @return string
	 */
	public function get_param( $key ) {
		$values = "";
		if ( isset( $_REQUEST[ $key ] ) ) {
			if ( is_array( $_REQUEST[ $key ] ) ) {
				$values  = [];
				$_values = [];
				foreach ( $_REQUEST[ $key ] as $val_key => $val ) {
					$_values[ $val_key ] = esc_attr( $val );
				}
				$values = $_values;
			} else {
				$values = esc_attr( $_REQUEST[ $key ] );
			}
		}
		if ( ! $values ) {
			$values = $this->default_search_query_vars[ $key ];
		}

		return $values;
	}

	/**
	 * 表示件数を出力
	 */
	public function result_count() {
		$paged = get_query_var( 'paged' ) - 1;
		$ppp   = get_query_var( 'posts_per_page' );
		$count = $total = $this->query->post_count;
		$from  = 0;
		if ( 0 < $ppp ) {
			$total = $this->query->found_posts;
			if ( 0 < $paged ) {
				$from = $paged * $ppp;
			}
		}
		printf(
			'<div class="c-block-main__hit"><span>該当件数</span><span class="is-hit">%1$s</span><span>件（%2$s%3$s件）</span></div>',
			$total,
			( 1 < $count ? ( $from + 1 . '〜' ) : '' ),
			( $from + $count )
		);
	}

	/**
	 * 現在のクエリを取得
	 *
	 * @param $key
	 *
	 * @return array|mixed
	 */
	public function get_query_var( $key ) {
		if ( isset( $this->current_search_query_vars[ $key ] ) ) {
			return $this->current_search_query_vars[ $key ];
		}

		return [];
	}

	/**
	 * 現在のURLにパラメータを追加する
	 * ex. http://localhost/
	 *     add_query_arg(["test_a"=> "1"]) => http://localhost/?test_a=1
	 * ex. http://localhost/?test_a=1
	 *     add_query_arg(["test_b"=> "2"]) => http://localhost/?test_a=1&test_b=2
	 *
	 * @param $params
	 *
	 * @return string
	 */
	public static function add_query_arg( $params ) {
		return add_query_arg( $params );
	}


	/**
	 * 検索クエリを取得
	 *
	 * @param $param_name
	 *
	 * @return string
	 */
	public function get_search_param_name( $param_name ) {
		return $this->query_prefix . $param_name;
	}

	/**
	 * 今のアーカイブページと、チェックボックスで出力している項目が合致するか判断
	 *
	 * @param $taxonomy
	 * @param $value
	 *
	 * @return bool
	 */

	public function is_checked_term( $taxonomy, $value ) {
		$param_key = $this->get_search_param_name( $taxonomy );
		$values    = $this->get_query_var( $param_key );
		if ( is_array( $values ) && in_array( $value, $values ) ) {
			return true;
		}

		return false;
	}

	/**
	 * 今のアーカイブページと、セレクトボックス、ラジオボタンで出力している項目が合致するか判断
	 *
	 * @param $taxonomy
	 * @param $value
	 *
	 * @return bool
	 */
	public function is_selected_term( $taxonomy, $value ) {
		$param_key = $this->get_search_param_name( $taxonomy );
		$values    = $this->get_query_var( $param_key );

		if ( $values && (int) $value === (int) $values ) {
			return true;
		}

		return false;
	}

	/**
	 * checked=true を出力
	 *
	 * @param $taxonomy
	 * @param $value
	 *
	 * @return string
	 */
	public function get_checked_prop_term( $taxonomy, $value ) {
		if ( $this->is_checked_term( $taxonomy, $value ) ) {
			return ' checked="true"';
		}

		return "";
	}

	/**
	 * is-active を出力
	 *
	 * @param $taxonomy
	 * @param $value
	 *
	 * @return string
	 */
	public function get_checked_class( $taxonomy, $value ) {
		if ( $this->is_checked_term( $taxonomy, $value ) ) {
			return ' is-active';
		}

		return "";
	}

	/**
	 * 現状のアーカイブページで、セレクトボックスの項目が選択されている場合は selected=trueを返す
	 *
	 * @param $taxonomy
	 * @param $value
	 *
	 * @return string
	 */
	public function get_selected_prop_term( $taxonomy, $value ) {
		if ( $this->is_selected_term( $taxonomy, $value ) ) {
			return ' selected="true"';
		}

		return "";
	}


	public function set_sort_options() {

	}

	abstract function filter_query( $query );

	/**
	 * tax_query を生成
	 * $taxonomies に指定しているtaxonomyから変換する
	 *
	 * @param string $relation 2つ以上のタクソノミー検索条件（内側の配列）が含まれる場合に、それらの論理的な関係を指定。有効な値は 'AND' または 'OR'
	 * @param string $field タクソノミータームの種類を選択。有効な値は 'term_id'（デフォルト）、'name'、'slug' または 'term_taxonomy_id' 。
	 *
	 * @return array
	 */
	public function get_tax_query( $relation = "AND", $field = "term_id" ) {
		$_tax_query = [];
		foreach ( $this->taxonomies as $taxonomy ) {
			$param_key = $this->get_search_param_name( $taxonomy );
			$value = $this->get_query_var( $param_key );
			if ( $value ) {
				$_tax_query[] = [
					'taxonomy'         => $taxonomy,
					'terms'            => $value,
					'field'            => $field,
					'include_children' => true, // 階層を持つタクソノミーの場合に子孫タクソノミーを含めるかどうか
					'operator'         => "IN", // 演算子。使用可能な値は 'IN'(デフォルト), 'NOT IN', 'AND', 'EXISTS' (4.1.0以降) と 'NOT EXISTS'(4.1.0以降)
				];
			}
		}

		return $_tax_query;
	}


	/**
	 * このフィルタが有効か判断
	 *
	 * @param WP_Query $query
	 *
	 * @return bool
	 */
	public function is_filter_query_enabled() {
		foreach ( $this->taxonomies as $taxonomy ) {
			if ( $this->query->is_tax( $taxonomy ) ) {
				return true;
			}
		}
		if ( $this->query->is_post_type_archive( $this->post_type ) ) {
			return true;
		}

		return false;
	}


	/**
	 * カスタムフィールド、タクソノミーを含めキーワードでの絞り込み検索をかける
	 *
	 * @param $orig_search
	 * @param $query
	 *
	 * @return mixed|string
	 */
	public function posts_search_custom_fields( $orig_search, $query ) {
		if ( ! is_admin() && $query->is_post_type_archive( $this->post_type ) ) {
			global $wpdb;
			$q         = $query->query_vars;
			$n         = ! empty( $q['exact'] ) ? '' : '%';
			$searchand = '';
			$search    = "";

			if ( $q['search_terms'] ) {
				foreach ( $q['search_terms'] as $term ) {
					$include = '-' !== substr( $term, 0, 1 );
					if ( $include ) {
						$like_op  = 'LIKE';
						$andor_op = 'OR';
					} else {
						$like_op  = 'NOT LIKE';
						$andor_op = 'AND';
						$term     = substr( $term, 1 );
					}
					$like      = $n . $wpdb->esc_like( $term ) . $n;
					$search    .= $wpdb->prepare( "{$searchand}(($wpdb->posts.post_title $like_op %s) $andor_op ($wpdb->posts.post_content $like_op %s) $andor_op (custom.meta_value $like_op %s)",
						$like,
						$like, $like );
					$search    .= " OR (
						   {$wpdb->posts}.ID IN (
							 SELECT distinct r.object_id
							 FROM {$wpdb->term_relationships} AS r
							 INNER JOIN {$wpdb->term_taxonomy} AS tt ON r.term_taxonomy_id = tt.term_taxonomy_id
							 INNER JOIN {$wpdb->terms} AS t ON tt.term_id = t.term_id
							 WHERE t.name LIKE '{$like}'
						   OR t.slug LIKE '{$like}'
						   OR tt.description LIKE '{$like}'
						   )
					   ) )";
					$searchand = ' AND ';
				}
				if ( ! empty( $search ) ) {
					$search = " AND ({$search}) ";
					if ( ! is_user_logged_in() ) {
						$search .= " AND ($wpdb->posts.post_password = '') ";
					}
				}
			}

			return $search;
		} else {
			return $orig_search;
		}
	}

	/**
	 * カスタムフィールドすべてへの検索
	 *
	 * @param $join
	 * @param $query
	 *
	 * @return mixed|string
	 */
	public function posts_join_custom_fields( $join, $query ) {
		if ( ! is_admin() && $query->is_post_type_archive( $this->post_type ) ) {
			global $wpdb;
			$wpdb->query( 'SET SESSION group_concat_max_len = 10000' );

			$join .= " INNER JOIN ( ";

			// 検索クエリ数が多い場合は除外する
			$join .= " SELECT post_id, group_concat( meta_value separator ' ') AS meta_value FROM $wpdb->postmeta ";
			// ここに除外するカスタムフィールドキーを追加
//			$join .= " WHERE meta_key NOT IN ( 'p_concept', 'p_similar', 'p_options' ) AND meta_key NOT LIKE '\_%' ";

			$join .= " GROUP BY post_id ";
			$join .= " ) AS custom ON ($wpdb->posts.ID = custom.post_id) ";
		}

		return $join;
	}

	public function get_posts() {
		if ( $this->query ) {
			return $this->query->get_posts();
		}

		return [];
	}

	public function have_posts() {
		if ( $this->query ) {
			return $this->query->have_posts();
		}

		return false;
	}

	public function the_post() {
		if ( $this->query ) {
			return $this->query->the_post();
		}

		return false;
	}

}
