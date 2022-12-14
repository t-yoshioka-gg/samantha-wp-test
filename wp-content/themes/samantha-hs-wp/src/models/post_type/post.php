<?php

/**
 * 投稿タイプごとのモデルクラス
 */
class gm_post extends gm_base_post {

	public $post_type = "post";

	public $fields = [];

	public $taxonomies = [
		'category',
		'post_tags',
	];

	public $terms = [];

	public function get_list_image() {
		return $this->get_field_value( "product_image" );
	}

	/**
	 * URL取得用のメソッドを投稿のカスタムフィールドによって取得するように変更
	 *
	 * @return false|mixed|string
	 */
	public function get_permalink() {
		$post_url  = parent::get_permalink(); // TODO: Change the autogenerated stub
		$link_data = $this->get_field( "c_links" );
		if ( ! $link_data || $link_data["type"] == "normal" ) {
			// 投稿記事を表示
		} else if ( $link_data["type"] == "file" ) {
			$file = wp_get_attachment_url( $link_data["link_file"] );
			if ( $file ) {
				return $file;
			}
		} else if ( $link_data["type"] == "url" ) {
			if ( $link_data["link_url"] ) {
				return $link_data["link_url"];
			}
		}

		return $post_url;
	}

	/**
	 * サムネイル画像のURLを取得
	 *
	 * @param string $size
	 *
	 * @return false|string
	 */
	public function get_thumbnail_url( $size = "full" ) {
		if ( ! get_post_thumbnail_id( $this->post_id ) ) {
			$first_image = $this->get_first_image();
			if ( $first_image ) {
				return $first_image;
			}
		}

		return GTag::get_thumbnail_url( $this->post_id, $size );
	}

	/**
	 * 投稿用のURLオプションが有効かどうか
	 * @return bool
	 */
	public function is_info_link_enable() {
		$link_data = $this->get_field( "c_links" );
		if ( ! $link_data || $link_data["type"] == "none" ) {
			return false;
		}

		return true;
	}

	/**
	 * 投稿のURLのtarget属性をカスタムフィールド別に取得
	 * @return string
	 */
	public function get_link_target() {
		$target    = "_self";
		$link_data = $this->get_field( "c_links" );

		if ( ! $link_data || $link_data["type"] == "normal" ) {
			// 投稿記事を表示
		} else if ( $link_data["type"] == "file" ) {
			$file = wp_get_attachment_link( $link_data["link_file"] );
			if ( $file ) {
				$target = "_blank";
			}
		} else if ( $link_data["type"] == "url" ) {

			if ( self::is_file_link( $link_data["link_url"] ) ) {
				$target = "_blank";
			}
			if ( self::is_external_link( $link_data["link_url"] ) ) {
				$target = "_blank";
			}
		}

		return $target;
	}


	/**
	 * ファイルリンクか判断
	 *
	 * @param $url
	 *
	 * @return bool
	 */
	public static function is_file_link( $url ) {
		$type = [ 'jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'xls', 'doc', 'xls', 'docx', 'xlsx' ];
		$ext  = explode( ".", $url );
		if ( $ext && count( $ext ) > 0 && in_array( $ext[ count( $ext ) - 1 ], $type ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * リンクのラベルを取得
	 * @return string
	 */
	public function get_link_label() {
		$label     = " ページを表示 ";
		$link_data = $this->get_field( "c_links" );
		if ( $link_data["type"] == "file" ) {
			$label = " ファイルを表示 ";
		} else if ( $link_data["type"] == "url" ) {
			$url = self::get_link_url();

			if ( self::is_file_link( $url ) ) {
				$label = " ファイルを表示 ";
			}
		}
		$label .= self::get_link_icon();

		return $label;
	}

	/**
	 * 投稿一覧でタイトル箇所に表示するアイコンを取得
	 * @return string
	 */
	public function get_link_icon() {
		$link_data = $this->get_field( "c_links" );
		$icon      = "";
		if ( ! $link_data ) {
			return "";
		}
		if ( $link_data["type"] == "url" ) {
			if ( $link_data["link_url"] && self::is_external_link( $link_data["link_url"] ) ) {
				$icon = "fa-external-link";
			}
		}
		if ( $link_data["type"] == "file" ) {
			if ( ! $link_data || empty( $link_data["icon"] ) ) {
				return "";
			}
			$icon = $link_data["icon"];
		}
		if ( empty( $icon ) ) {
			return "";
		}

		return '<i class="fa ' . $icon . '" aria-hidden="true"></i>';
	}

	/**
	 * 外部リンクかどうか判断
	 *
	 * @param $url
	 *
	 * @return bool
	 */
	public static function is_external_link( $url ) {
		if ( strpos( $url, get_site_url() ) !== false // 自社サイトのURLが含まれていれば内部リンク
		     || strpos( $url, "://" ) === false ) { // ://が含まれていなければ内部リンク

			return false;
		} else {
			return true;
		}
	}



}
