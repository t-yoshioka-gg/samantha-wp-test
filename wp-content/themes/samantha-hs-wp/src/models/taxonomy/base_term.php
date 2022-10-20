<?php
/**
 * タームモデルの基幹クラス
 *
 */
abstract class gm_base_term {

	/***
	 * タクソノミー名
	 * @var string
	 */
	public $taxonomy = "";

	public static $_taxonomy = "";

	/**
	 * タームID
	 * @var int|mixed
	 */
	public $term_id = 0;

	/**
	 * タームのオブジェクト
	 * get_term での取得値と同様
	 * 
	 * @var WP_Term
	 * - term_id
	 * - slug
	 * - description
	 * - parent
	 * - count
	 * - taxonomy
	 */
	public $term = null;

	/**
	 * タームのacfカスタムフィールドの保存プロパティ
	 * @var array
	 */
	public $fields = [];

	/**
	 * タームのカスタムフィールドの保存プロパティ
	 * @var array
	 */
	public $metas = [];

	/**
	 * 初期化
	 *
	 * @param int $term_id タームIDを指定。ただし、WP_Termオブジェクトでも動作する
	 */
	public function __construct( $term_id ) {
		if ( is_object( $term_id ) && isset( $term_id->term_id ) ) {
			$term_id = $term_id->term_id;
		}
		$this->term_id = $term_id;
		$this->term    = WP_Term::get_instance( $term_id );
		$this->set_fields();
		$this->set_metas();

	}


	/**
	 * ゲッター
	 *
	 * @param $name
	 *
	 * @return mixed|void
	 *
	 */
	public function __get( $name ) {
		if ( isset( $this->term->{$name} ) ) {
			return $this->term->{$name};
		}

		return new ErrorException( "存在しないプロパティにアクセスしようとしています" );
	}

	/**
	 * マジックメソッド
	 *
	 * @param $name
	 * @param $args
	 *
	 * @return ErrorException|false|mixed|string|void
	 */
	public function __call( $name, $args ) {
		$type = substr( $name, 0, 3 );
		$prop = substr( $name, 4, 100 );
		if ( $type === "get" ) {
			if ( isset( $this->{$prop} ) ) {
				return $this->{$prop};
			}
			if ( isset( $this->term->{$prop} ) ) {
				return $this->term->{$prop};
			}
			if ( isset( $this->fields[ $prop ] ) ) {
				if ( $this->fields[ $prop ]["type"] === "image" && $this->fields[ $prop ]["return_format"] === "id" ) {
					return wp_get_attachment_image_url( $this->fields[ $prop ]["value"], "full" );
				}
				if ( $this->fields[ $prop ]["type"] === "image" && $this->fields[ $prop ]["return_format"] === "array" ) {
					return $this->fields[ $prop ]["value"]["url"];
				}

				return $this->fields[ $prop ]["value"];
			}
		}

		return new ErrorException( "存在しないプロパティにアクセスしようとしています" );
	}

	/**
	 * タームのカスタムフィールドをセット
	 */
	public function set_fields() {
		$this->fields = get_field_objects( $this->term );
	}

	/**
	 * ターム名を取得
	 * @return string
	 */
	public function get_name() {
		return $this->term->name;
	}

	/**
	 * タームIDを取得
	 * 
	 * @return integer
	 */
	public function get_term_id() {
		return $this->term_id;
	}

	/**
	 * スラッグを取得
	 * @return string
	 */
	public function get_slug() {
		return $this->term->slug;
	}

	/**
	 * 説明文を取得
	 * 
	 * @return string
	 * 
	 */
	public function get_description() {
		return $this->term->description;
	}

	/**
	 * 記事のカウント数を取得
	 * @return integer
	 */
	public function get_count() {
		return $this->term->count;
	}

	/**
	 * 親のタームを取得
	 */
	public function get_parent() {
		return $this->term->parent;
	}

	/**
	 * カスタムフィールドをセットする
	 * 
	 * @return static
	 * 
	 */
	public function set_metas() {
		$this->metas = get_option( "taxonomy_" . $this->term_id );
		return $this;
	}

	/**
	 * カスタムフィールドの値を取得する
	 *
	 * @param string $key カスタムフィールドキーを取得
	 * @param string $default 値がない場合の初期値
	 * @param boolean $raw フィルターをかけずにそのまま取得する際には true を指定
	 *
	 * @return mixed|string
	 */
	public function get_field( $key, $default = "", $raw = false ) {
		if ( isset( $this->fields[ $key ]["value"] ) && $this->fields[ $key ]["value"] ) {
			if ( $raw === false ) {
				if ( $this->fields[ $key ]["type"] === "taxonomy" ) {
					if ( ! is_array( $this->fields[ $key ]["value"] ) ) {
						$term = get_term( $this->fields[ $key ]["value"] );

						return $term->name;
					}
					if ( is_array( $this->fields[ $key ]["value"] ) ) {
						$terms = [];
						foreach ( $this->fields[ $key ]["value"] as $term_id ) {
							$terms[] = get_term( $term_id );
						}

						return $terms;
					}
				}
				if ( $this->fields[ $key ]["type"] === "image" && is_numeric( $this->fields[ $key ]["value"] ) ) {
					return wp_get_attachment_image_url( $this->fields[ $key ]["value"], "full" );
				}
			}
			return $this->fields[ $key ]["value"];
		}
		return $default;
	}

	/**
	 * カスタムフィールドの値を取得する
	 *
	 * @deprecated 命名規則の統一のために、get_fieldを利用すること
	 * @param string $key acfのカスタムフィールドキー
	 * @param string $default
	 * @param boolean $raw
	 * @return mixed|string
	 */
	public function get_field_value( $key, $default = "", $raw = false ) {
		return $this->get_field( $key, $default, $raw );
	}
	/**
	 * カスタムフィールドの値を取得
	 *
	 * @param $key
	 * @param string $default
	 *
	 * @return mixed|string
	 */
	public function get_meta_value( $key, $default = "" ) {
		if ( isset( $this->metas[ $key ] ) && $this->metas[ $key ] ) {
			return $this->metas[ $key ];
		}

		return $default;
	}


	/**
	 * 一覧ページのURを取得
	 *
	 * @return string|WP_Error
	 */
	public function get_archive_link() {
		return get_term_link( $this->term );
	}

	/**
	 * 現在のタームを取得する
	 *
	 * @param bool $post_id
	 * @param bool $only
	 *
	 * @return array|mixed
	 */
	public static function get_current_term( $post_id = false, $only = true ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		$categories = get_the_terms( $post_id, static::class );
		$terms      = [];
		foreach ( $categories as $category ) {
			$terms[] = new static( $category->term_id );
		}
		if ( $only && $terms && isset( $terms[0] ) && $terms[0] ) {
			return $terms[0];
		}
		return $terms;
	}

	/**
	 * このタームのアーカイブページか判断
	 *
	 * @return bool
	 */
	public function is_tax() {
		if ( $this->taxonomy === "category" ) {
			return is_category( $this->term_id );
		}
		if ( $this->taxonomy === "post_tag" ) {
			return is_tag( $this->term_id );
		}
		return ( is_tax( $this->taxonomy, [ $this->term_id ] ) );
	}

	/**
	 * このタームが紐付けられている記事を取得する
	 * 
	 * @return object WP_Query
	 * 
	 */
	public function get_posts_query( $post_type, $posts_per_page = 6 ) {
		return new WP_Query( [
			'post_type'      => $post_type,
			'posts_per_page' => $posts_per_page,
			'tax_query'      => [
				[
					'taxonomy' => $this->taxonomy,
					'terms'    => $this->term_id,
				]
			]
		] );
	}


	/**
	 * 小カテゴリを取得する
	 * 
	 * @return array
	 * 
	 */
	public function get_children_terms( $args = [] ) {
		$default_args = [
			'parent'   => $this->term_id,
			'taxonomy' => $this->taxonomy
		];
		$args         = wp_parse_args( $args, $default_args );
		$terms        = get_terms( $args );

		$_terms = [];
		if ( $terms ) {
			foreach ( $terms as $term ) {
				$_terms[] = new static( $term->term_id );
			}
		}

		return $_terms;
	}

	/**
	 * タームを取得する(get_termsのエイリアス)
	 */
	public static function get_terms( $args = [] ) {
		$args       = wp_parse_args( $args, [
			"taxonomy"   => static::$_taxonomy,
			'hide_empty' => false
		] );
		$terms      = get_terms( $args );
		$repository = [];
		foreach ( $terms as $term ) {
			$repository[] = new static( $term->term_id );
		}

		return $repository;
	}

	/**
	 * すべてのタームを取得する
	 *
	 * @return array(static,static,static...)
	 *
	 */
	public static function get_all_items() {
		return static::get_terms( [
			'hide_empty' => false
		] );
	}

	/**
	 * 検索時のタクソノミーキーを取得
	 * @return string
	 */
	public function get_search_tax_key() {
		return "s_" . static::$_taxonomy;
	}

	/**
	 * 検索時のパラメータを取得
	 * @return array
	 */
	public function get_search_param() {
		if ( isset( $_GET[ $this->get_search_tax_key() ] ) && $_GET[ $this->get_search_tax_key() ] ) {
			$search_terms = $_GET[ $this->get_search_tax_key() ];
			if ( is_array( $search_terms ) ) {
				$search_terms = array_map( "esc_html", $search_terms );
				$search_terms = array_map( "intval", $search_terms );
			} else {
				$search_terms = esc_html( $search_terms );
			}

			return $search_terms;
		}

		return [];
	}

	/**
	 * 現状選択されているか(チェックボックスの場合)
	 *
	 * @param bool $is_radio ラジオボタンの場合は true
	 *
	 * @return string
	 */
	public function checked( $is_radio = false ) {
		$params = $this->get_search_param();
		if ( $this->is_tax() ) {
			return checked( true, true, false );
		}
		if ( $is_radio ) {
			return checked( true, ( $this->term_id == $params ), false );
		}

		return checked( true, in_array( $this->term_id, $params ), false );
	}

	/**
	 * 現状選択されているか(セレクトボックスの場合)
	 * @return string
	 */
	public function selected() {
		$params = $this->get_search_param();
		if(!$params){
			return ;
		}
		if ( $this->is_tax() ) {
			return selected( true, true, false );
		}
		return selected( $this->term_id, $params, false );
	}

	/**
	 * チェックボックスを生成
	 * @param $link
	 *
	 * @return void
	 */
	public function generate_checkbox( $link = true ) {
		$name = $this->get_search_tax_key() . "[]";
		?>
		<label>
			<input type="checkbox" name="<?php echo $name ?>" value="<?php echo $this->get_term_id() ?>" <?php echo $this->checked() ?>>
			<?php
			$name = $this->get_name();
			?>
			<span>
				<span><?php echo $name ?></span>
			</span>
		</label>
		<?php
	}

	/**
	 * セレクトボックスを変換
	 *
	 * @param $link
	 *
	 * @return void
	 */
	public function generate_selectbox( $link = true ) {
		?>
		<option <?php echo $this->selected() ?> value="<?php echo $this->get_term_id() ?>">
			<?php
				echo $this->get_name();
			?>
		</option>
		<?php
	}

	
}
