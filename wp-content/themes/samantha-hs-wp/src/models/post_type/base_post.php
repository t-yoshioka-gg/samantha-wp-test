<?php

/**
 * Class gm_base_post
 *
 * 投稿オブジェクトの基幹クラス
 *
 */
abstract class gm_base_post {

	/**
	 * 投稿ID
	 * @var bool | integer
	 */
	public $post_id = false;

	/**
	 * 投稿タイプ
	 * @var string
	 */
	public $post_type = "";

	/**
	 * 投稿オブジェクト
	 * https://wpdocs.osdn.jp/%E3%82%AF%E3%83%A9%E3%82%B9%E3%83%AA%E3%83%95%E3%82%A1%E3%83%AC%E3%83%B3%E3%82%B9/WP_Post
	 *
	 * @var WP_Post
	 * - ID
	 * - post_author
	 * - post_date
	 * - post_date_gmt
	 * - post_content
	 * - post_title
	 * - post_excerpt
	 * - post_status
	 * - comment_status
	 * - ping_status
	 * - post_password
	 * - post_name
	 * - to_ping
	 * - pinged
	 * - post_modified
	 * - post_modified_gmt
	 * - post_content_filtered
	 * - post_parent
	 * - guid
	 * - menu_order
	 * - post_type
	 * - post_mime_type
	 * - comment_count
	 */
	public $post = null;

	/**
	 * AdvancedCustomFieldsで管理するフィールドデータを格納
	 * @var array
	 */
	public $fields = [];

	/**
	 * タクソノミー
	 * @var array
	 */
	public $taxonomies = [];

	/**
	 * ターム
	 * @var array
	 */
	public $terms = [];


	/**
	 * gm_base_post constructor.
	 * 初期化
	 *
	 * @param $post_id
	 */
	public function __construct( $post_id ) {
		$this->post_id = $post_id;
		$this->post    = get_post( $post_id );
		$this->set_fields();
		$this->set_terms();
	}

	/**
	 * 現在の記事を取得
	 * @return self
	 */
	public static function from_global() {
		return new static( get_the_ID() );
	}


	/**
	 * マジックメソッド
	 * get_{プロパティ名} : ターム内、カスタムフィールド内、投稿オブジェクト内から取得
	 *   ex. $this->get_ID() : $this->post->ID
	 *       $this->get_c_name() : $this->fields["c_name"]
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
			if ( isset( $this->post->{$prop} ) ) {
				return $this->post->{$prop};
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
	 * ゲッター用メソッド
	 * $this->ID など、存在しないプロパティを取得しようとした時、
	 * $this->post プロパティ内に存在していれば取得する
	 *
	 * @param $name
	 *
	 * @return mixed|void
	 *
	 */
	public function __get( $name ) {
		if ( isset( $this->post->{$name} ) ) {
			return $this->post->{$name};
		}

		return new ErrorException( "存在しないプロパティにアクセスしようとしています" );
	}

	/**
	 * ACFを利用してカスタムフィールドをセットする。
	 * get_field_objects : https://www.advancedcustomfields.com/resources/get_field_objects/
	 * ACFで管理しているカスタムフィールドをすべて取得する
	 */
	public function set_fields() {
		$this->fields = get_field_objects( $this->post_id );
	}

	/**
	 * 記事に紐づくタームの情報を取得する
	 *
	 * @return array
	 */
	public function set_terms() {
		if ( ! $this->taxonomies ) {
			return [];
		}
		foreach ( $this->taxonomies as $taxonomy ) {
			$terms  = [];
			$_terms = get_the_terms( $this->post_id, $taxonomy );
			if ( $_terms ) {
				foreach ( $_terms as $term ) {

					if ( ! isset( $term->term_id ) ) {
						continue;
					}
					$classname = "gm_" . $term->taxonomy;
					if ( class_exists( $classname ) ) {
						$terms[] = new $classname( $term->term_id );
					} else {
						$terms[] = $term;
					}
				}
				$this->terms[ $taxonomy ] = $terms;
			}
		}
	}

	/**
	 * IDを取得
	 */
	public function get_ID() {
		return $this->post_id;
	}

	/**
	 * 著者IDを取得
	 *
	 * @return integer
	 */
	public function get_post_author() {
		return $this->post->post_author;
	}

	/**
	 * スラッグを取得
	 *
	 * @return string
	 */
	public function get_post_name() {
		return $this->post->post_name;
	}

	/**
	 * 投稿タイプを取得
	 *
	 * @return string
	 */
	public function get_post_type() {
		return $this->post->post_type;
	}

	/**
	 * 投稿ステータスを取得
	 *
	 * @return string publish, draft, private, trash
	 */
	public function get_post_status() {
		return $this->post->post_status;
	}

	/**
	 * 親の記事IDを取得
	 *
	 * @return integer WP_Post.ID
	 */
	public function get_post_parent() {
		return $this->post->post_parent;
	}


	/**
	 * 最終更新日時を取得
	 *
	 * @return string datestring
	 */
	public function get_post_modified() {
		return $this->post->post_modified;
	}

	/**
	 * コメントのカウント数を取得
	 *
	 * @return integer
	 */
	public function get_comment_count() {
		return $this->post->comment_count;
	}

	/**
	 * 順序値を取得
	 *
	 * @return integer
	 */
	public function get_menu_order() {
		return $this->post->menu_order;
	}

	/**
	 * パーマリンクを出力
	 *
	 * @return false|string
	 */
	public function the_permalink() {
		echo $this->get_permalink();
	}

	/**
	 * パーマリンクを取得
	 *
	 * @return false|string
	 */
	public function get_permalink() {
		return get_the_permalink( $this->post_id );
	}

	/**
	 * WordPressのテンプレート関数に合わせるため非推奨に
	 *
	 * @deprecated 2.0.1
	 * @param string $format
	 *
	 * @return false|string
	 */
	public function get_post_date( $format = "Y.m.d" ) {
		return get_the_date( $format, $this->post_id );
	}


	/**
	 * 投稿日を取得
	 *
	 * @param string $format
	 *
	 * @return false|string
	 */
	public function get_the_date( $format = "Y.m.d" ) {
		return get_the_date( $format, $this->post_id );
	}

	/**
	 * 投稿タイトルを取得する
	 *
	 * @param int $trim_width
	 *
	 * @return string
	 * @deprecated 2.0.1
	 */
	public function get_post_title( $trim_width = 0 ) {
		return $this->get_the_title( $trim_width );
	}

	/**
	 * 投稿タイトルを取得する
	 *
	 * @param int $trim_width
	 * @param string $trim_marker
	 *
	 * @return string
	 */
	public function get_the_title( $trim_width = 0, $trim_marker = '&hellip;' ) {
		if ( $trim_width ) {
			return esc_html( mb_strimwidth( $this->post->post_title, 0, $trim_width, $trim_marker ) );
		}

		return esc_html( $this->post->post_title );
	}

	/**
	 * 記事の抜粋文を返す
	 * フィルターなど有効にするためにget_the_excerptを利用
	 * @return string
	 */
	public function get_the_excerpt() {
		return get_the_excerpt( $this->post_id );
	}

	/**
	 * 記事の抜粋文を出力
	 * @return void
	 */
	public function the_excerpt() {
		echo $this->get_the_excerpt();
	}


	/**
	 * 記事本文をを各種フィルターを適用した上で取得
	 * @return array|string|string[]
	 */
	public function get_content() {
		$content = get_the_content( null, null, $this->post_id );
		$content = apply_filters( "the_content", $content );
		$content .= $this->get_link_pages();
		return str_replace( ']]>', ']]&gt;', $content );
	}

	/**
	 * 記事本文を出力
	 */
	public function the_content() {
		echo $this->get_content();
	}

	/**
	 * カスタムフィールド(ACF) 自体を取得
	 * @return array カスタムフィールドのキーと値を含んだ配列を取得
	 */
	public function get_fields() {
		return $this->fields;
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
	 * @param string $key acfのカスタムフィールドキー
	 * @param string $default
	 * @param boolean $raw
	 *
	 * @return mixed|string
	 * @deprecated 命名規則の統一のために、get_fieldを利用すること
	 */
	public function get_field_value( $key, $default = "", $raw = false ) {
		return $this->get_field( $key, $default, $raw );
	}

	/**
	 * カスタムフィールドを持っているか判断する
	 *
	 * @param string $key キーを指定
	 *
	 * @return int
	 * @example echo $this->has( "c_example" ) ? "1" : 0;
	 */
	public function has( $key ) {
		return ( $this->get_field( $key ) ) ? 1 : 0;
	}


	/**
	 * 投稿に紐づくタクソノミーのタームを取得する
	 *
	 * @param string $taxonomy タクソノミーのスラッグを取得する
	 *
	 * @return mixed
	 */
	public function get_terms( $taxonomy ) {
		if ( isset( $this->terms[ $taxonomy ] ) ) {
			return $this->terms[ $taxonomy ];
		}

		return [];
	}

	/**
	 * タームに対して処理を行う
	 */
	public function the_terms_map( $taxonomy, $callback ) {
		if ( isset( $this->terms[ $taxonomy ] ) && is_callable( $callback ) ) {
			foreach ( $this->terms[ $taxonomy ] as $term ) {
				$callback( $term );
			}
		}

		return false;
	}

	/**
	 * ACFに限らない、投稿に紐づく生のカスタムフィールドデータを取得
	 *
	 * @param $key
	 * @param string $default
	 * @param bool $single
	 *
	 * @return mixed|string
	 */
	public function get_meta( $key, $default = "", $single = true ) {
		$value = get_post_meta( $this->post_id, $key, $single );
		if ( ! $value ) {
			return $default;
		}

		return $value;
	}

	/**
	 * ACFに限らない、投稿に紐づくすべてのカスタムフィールドデータを取得
	 *
	 * @return array
	 */
	public function get_metas() {
		return get_post_custom( $this->post_id );
	}

	/**
	 * サムネイル画像のURLを取得
	 *
	 * @param string $size
	 *
	 * @return false|string
	 */
	public function get_thumbnail_url( $size = "full" ) {
		return GTag::get_thumbnail_url( $this->post_id, $size );
	}


	/**
	 * 最初のタームを取得する
	 *
	 * @param $taxonomy
	 *
	 * @return mixed|null
	 */
	public function get_first_term( $taxonomy ) {
		$terms = $this->get_terms( $taxonomy );

		if ( $terms && isset( $terms[0] ) ) {
			return $terms[0];
		}

		return null;
	}

	/**
	 * 最初のターム名を取得する
	 *
	 * @param $taxonomy
	 *
	 * @return bool
	 */
	public function get_first_term_name( $taxonomy ) {
		$first_term = $this->get_first_term( $taxonomy );

		if ( $first_term && isset( $first_term->name ) ) {
			return $first_term->name;
		}
		if ( $first_term && $first_term->get_name() ) {
			return $first_term->get_name();
		}

		return false;
	}


	/**
	 * 記事本文を含めて最初の画像を取得
	 *
	 * @param string $content
	 *
	 * @return mixed|string
	 */
	public function get_first_image( $content = "" ) {
		$first_img = '';
		if ( ! $content ) {
			$content = $this->post->post_content;
		}
		ob_start();
		ob_end_clean();
		preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches );
		if ( isset( $matches[1][0] ) ) {
			$first_img = $matches[1][0];
		}

		return $first_img;
	}

	/**
	 * 現在のプロパティを元に記事情報を更新
	 */
	public function update() {
		$post  = $this->post;
		$_post = [];
		foreach ( $post as $key => $p ) {
			$_post[ $key ] = $p;
		}
		$post_id = wp_update_post( $_post );
		foreach ( $this->fields as $field_key => $field_value ) {
			update_post( $field_key, $field_value["value"], $post_id );
		}
	}

	/**
	 * wp_link_pages のエイリアス
	 *
	 * @return string
	 */
	public function get_link_pages() {
		return  wp_link_pages( [
			'before' => '<div class="page-links">' . __( 'Pages:', 'growp' ),
			'after'  => '</div>',
			'echo' => false
		] );
	}

	/**
	 * Add to Any を利用したソーシャルアイコン出力
	 *
	 * @return void
	 */
	public function the_social_icons() {
		if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) {
			ADDTOANY_SHARE_SAVE_KIT();
		}
	}

	/**
	 * ページネーションのエイリアス
	 */
	public function the_paging_nav( $prev_text = "<i class=\"fa fa-angle-left\" aria-hidden=\"true\"></i>", $next_text = "<i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i>" ) {
		echo GNav::get_paging_nav( $prev_text, $next_text );
	}


	/**
	 * GNav::the_post_nav() へのエイリアス
	 */
	public static function the_post_nav( $taxonomy = "category", $prev_text = "前の記事へ", $next_text = "次の記事へ", $list_text = "記事一覧へ" ) {
		echo GNav::get_post_nav( $taxonomy, $prev_text, $next_text, $list_text );
	}

	/**
	 * YARPPプラグインを利用し関連記事を取得する
	 *
	 * @return array
	 */
	public function get_related_posts( $args = [] ) {
		$related_posts = [];
		if ( function_exists( "yarpp_get_related" ) ) {
			$defaults = [
				"post_type" => $this->post_type,
			];
			$args = wp_parse_args( $args, $defaults );

			$related_posts = yarpp_get_related( $args );
		}

		return $related_posts;
	}

}
