<?php
/**
 * テンプレート関係
 */
class gm_template {

	private static $instance = null;

	/**
	 * 初期化
	 */
	private function __construct() {
//		add_filter( "init", [ $this, 'save_content' ] );
	}

	/**
	 * シングルトン
	 * @return null
	 */
	public function get_instance() {
		if ( static::$instance === null ) {
			static::$instance = new static();
		}

		return static::$instance;
	}


	/**
	 * テンプレートのパスを取得する
	 * @return mixed
	 */
	public static function get_template_path() {
		return GROWP_Theme_Wrapper::$main_template;
	}

	/**
	 * メインテンプレートの内容を呼び出す
	 * @return string
	 */
	public static function get_raw_content() {
		ob_start();
		load_template( static::get_template_path(), false );
		$templatedata = ob_get_contents();
		ob_end_clean();
		do_action( "growp_get_content", $templatedata );

		return $templatedata;
	}

	public static function get_content() {
		$content = do_shortcode( static::get_raw_content() );

		// 表示するコンテンツから改行・タブを削除するか？
		$do_trim = true;

		// mw_wp_formを表示しているページでは処理をやめる
		if ( mb_strpos( $content, "mw_wp_form" ) !== false ) {
			$do_trim = false;
		}
		// mw_wp_formを表示しているページでは処理をやめる
		if ( mb_strpos( $content, "<pre>" ) !== false ) {
			$do_trim = false;
		}

		// 整形処理
		// 改行は削除
		if ( $do_trim ) {
			$content = str_replace( "\t", "", $content );
			$content = str_replace( "\n", "", $content );
			$content = str_replace( "\r", "", $content );
		}
		return $content;
	}

	/**
	 * 記事を保存する
	 */
	public static function save_content() {
		if ( is_page() ) {
			$post_id = get_the_ID();
			$_post   = get_post( $post_id );
			if ( ! $_post->post_content ) {
				// 改行は取り除いた上で挿入する＜ビジュアルエディタからHTMLエディタに変えたときの変な改行を防ぐため＞
				$insert_content = str_replace( "\n", "", static::get_raw_content() );
				$insert_content .= '<div style="display:none;"><p>&nbsp;</p></div>';
				wp_update_post( [
					"ID"            => $post_id,
					"post_content"  => $insert_content,
					"page_template" => "template-allhtml.php"
				] );
			}
		}
	}
}
