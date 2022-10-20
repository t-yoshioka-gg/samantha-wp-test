<?php

class gm_site {

	/**
	 * サイトオプションのテンプレートを更新する
	 *
	 * @param string $key サイトオプションのカスタムフィールドキー
	 * @param callback $callback テンプレートを指定
	 */
	public static function update_template( $key, $callback = null ) {
		echo do_shortcode( get_field( $key, "option" ) );
		if ( is_callable( $callback ) ) {
			ob_start();
			$callback();
			$content = ob_get_contents();
			ob_end_clean();
			if ( trim( $content ) ) {
				update_field( $key, $content, "option" );
			}
		}
	}

	/**
	 * サイトオプションのページヘッダー設定への処理
	 *
	 * @return array
	 */
	public static function get_page_headers_options() {
		$page_headers = get_field( "o_site_page_headers", "option" );
		$options      = [];
		if ( ! $page_headers ) {
			return $options;
		}
		foreach ( (array) $page_headers as $item ) {
			/**
			 * 投稿タイプで指定する場合
			 */
			if ( $item["type"] === "h_post_type" ) {
				$post_types = $item["h_post_type"];

				$flag = false;
				foreach ( $post_types as $post_type ) {
					if ( $post_type === "post" ) {
						if ( is_home() || is_category() || is_tag() || is_singular( $post_type ) ) {
							$flag = true;
						}
					} else {
						if ( is_post_type_archive( $post_type ) || is_singular( $post_type ) ) {
							$flag = true;
						}
					}
				}

				if ( $flag ) {
					$options[] = [
						'title_main' => $item["h_title_main"],
						'title_sub'  => $item["h_title_sub"],
						'image'      => $item["h_image"],
					];
				}
			}
			/**
			 * 投稿タイプで指定する場合
			 */
			if ( $item["type"] === "h_taxonomy" ) {
				$taxonomies = $item["h_taxonomy"];
				foreach ( $taxonomies as $taxonomy ) {
					if ( is_tax( $taxonomy->name ) ) {
						$options[] = [
							'title_main' => $item["h_title_main"],
							'title_sub'  => $item["h_title_sub"],
							'image'      => $item["h_image"],
						];
					}
				}
			}
			/**
			 * URLで指定する場合
			 */
			if ( $item["type"] === "h_url" ) {
				$url = $item["h_url"];
				if ( isset( $_SERVER["REQUEST_URI"] ) ) {
					$current_uri = $_SERVER["REQUEST_URI"];
					if ( mb_strpos( $current_uri, $url ) !== false ) {
						$options[] = [
							'title_main' => $item["h_title_main"],
							'title_sub'  => $item["h_title_sub"],
							'image'      => $item["h_image"],
						];
					}

				}
			}
		}

		return $options;

	}

}
