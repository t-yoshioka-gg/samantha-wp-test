<?php
/**
 * その他必要な設定
 *
 * @package growp
 */

/**
 * body に付与するタグをカスタマイズ
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function growp_body_classes( $classes ) {
	global $post;

	// スラッグが設定されている場合出力
	if ( isset( $post->post_name ) ) {
		$classes[] = $post->post_name;
	}
	// マルチサイトの場合はサイトIDを追加
	if ( is_multisite() ){
		$classes[] = "site-" . get_current_blog_id();
	}

	return $classes;
}

add_filter( 'body_class', 'growp_body_classes' );


/**
 * 抜粋文をカスタマイズ
 *
 * @return void
 * @since 1.2.1
 */

add_action( 'excerpt_more', 'growp_change_more' );

function growp_change_more( $more ) {
	if ( 0 == get_theme_mod( 'single_char_num', 50 ) ) {
		return "";
	}
	$more = ' &hellip; <span class="c-button is-more">' . __( 'More', 'growp' ) . '</span>';

	return apply_filters( 'growp_readmore', $more );

}

/**
 * 抜粋文の長さをカスタマイズ
 *
 * @param $length
 *
 * @return int
 */
function growp_excerpt_length( $length ) {
	return 80;
}

add_filter( 'excerpt_length', 'growp_excerpt_length', 999 );


/**
 * コンポーネントをショートコードで呼び出し
 */
function growp_shortcode_get_component( $atts ) {
	$atts = shortcode_atts( array(
			'name' => '',
	), $atts, 'growp_component' );
	if ( empty( $atts["name"] ) ) {
		return "";
	}
	ob_start();
	GTemplate::get_component( $atts["name"] );
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

add_shortcode( 'growp_component', 'growp_shortcode_get_component' );


/**
 * AddToAny シェアボタンのメタボックスを表示しない
 */
function growp_remove_share_box() {
	$post_types = get_post_types( array( 'public' => true ) );
	remove_meta_box( 'A2A_SHARE_SAVE_meta', $post_types, 'side' );
}

add_action( 'add_meta_boxes', 'growp_remove_share_box', 40 );



/**
 * 管理メニューの削除
 *
 * @param $wp_admin_bar
 */
function growp_remove_bar_menus( $wp_admin_bar ) {
	if ( current_user_can( "administrator" ) ) {
		return;
	}
	//WordPressアイコン
	$wp_admin_bar->remove_menu( 'wp-logo' );
	//WordPressアイコン -> WordPress について
	$wp_admin_bar->remove_menu( 'about' );
	//WordPressアイコン -> WordPress.org
	$wp_admin_bar->remove_menu( 'wporg' );
	//WordPressアイコン -> ドキュメンテーション
	$wp_admin_bar->remove_menu( 'documentation' );
	//WordPressアイコン -> サポートフォーラム
	$wp_admin_bar->remove_menu( 'support-forums' );
	//WordPressアイコン -> フィードバック
	$wp_admin_bar->remove_menu( 'feedback' );

	//サイト情報
//	$wp_admin_bar->remove_menu( 'site-name' );
	//サイト情報 -> ダッシュボード
//	$wp_admin_bar->remove_menu( 'dashboard' );
	//サイト情報 -> テーマ
	$wp_admin_bar->remove_menu( 'themes' );
	//サイト情報 -> ウィジェット
	$wp_admin_bar->remove_menu( 'widgets' );
	//サイト情報 -> メニュー
	$wp_admin_bar->remove_menu( 'menus' );
	//サイト情報 -> ヘッダー
	$wp_admin_bar->remove_menu( 'header' );

	//カスタマイズ
	$wp_admin_bar->remove_menu( 'customize' );

	//コメント
	$wp_admin_bar->remove_menu( 'comments' );

	//新規
	$wp_admin_bar->remove_menu( 'new-content' );
	//新規 -> 投稿
	$wp_admin_bar->remove_menu( 'new-post' );
	//新規 -> メディア
	$wp_admin_bar->remove_menu( 'new-media' );
	//新規 -> 固定ページ
	$wp_admin_bar->remove_menu( 'new-page' );
	//新規 -> ユーザー
	$wp_admin_bar->remove_menu( 'new-user' );

	// Duplicate post
	$wp_admin_bar->remove_menu( 'duplicate-post' );
	$wp_admin_bar->remove_menu( 'new-draft' );
	$wp_admin_bar->remove_menu( 'rewrite-republish' );

	// Analytics
	$wp_admin_bar->remove_node( 'gainwp-1' );

	//〜の編集
//	$wp_admin_bar->remove_menu( 'edit' );

	//こんにちは、[ユーザー名]　さん
//	$wp_admin_bar->remove_menu( 'my-account' );
	//ユーザー -> ユーザー名・アイコン
//	$wp_admin_bar->remove_menu( 'user-info' );
	//ユーザー -> プロフィールを編集
//	$wp_admin_bar->remove_menu( 'edit-profile' );
	//ユーザー -> ログアウト
//	$wp_admin_bar->remove_menu( 'logout' );

	//検索
	$wp_admin_bar->remove_menu( 'search' );
}

add_action( 'admin_bar_menu', 'growp_remove_bar_menus', 99999 );


/**
 * '<div style=”display: none;”><p></p></div>'が post_contentにない場合は自動で挿入する
 *
 * @param array $data
 * @param array $postarr
 * @param array $unsanitized_postarr
 * @return void
 */
function growp_post_content_auto_insert_hidden_p_tag( $data = [], $postarr = [], $unsanitized_postarr = [] ) {
	$current_post_type = $data["post_type"];
	 // 固定ページのみ対象とする
	if ( $current_post_type !== "page" ) {
		return $data;
	}
	if ( isset( $data["post_content"] ) && $data["post_content"] ) {
		$re = '/<div.*?style=(|\\\\)"display:(|\s)none(|;)(|\\\\)">(|\n|\r|\r\n)<p>.*?<\/p>(|\n|\r|\r\n)<\/div>/m';
		$hide_p_tag = '<div style="display:none;"><p>&nbsp;</p></div>';
		$count = preg_match_all($re, $data["post_content"], $matches, PREG_SET_ORDER, 0);
		// echo var_dump($matches);
		// exit;
		if ( ! $matches ) {
			$data["post_content"] = $data["post_content"] . $hide_p_tag;
		}
	}
	return $data;
}
add_action( "wp_insert_post_data", "growp_post_content_auto_insert_hidden_p_tag", 10, 3 );

/**
 * ログインしているユーザだけに表示するショートコード
 * [if-login] このメッセージはログインしているユーザだけに表示されます [/if-login]
 *
 * @param $atts
 * @param null $content
 *
 * @return string
 */
function growp_if_login( $atts, $content = null ) {
	if ( is_user_logged_in() ) {
		return '' . do_shortcode( $content ) . '';
	} else {
		return '';
	}
}

add_shortcode( 'if-login', 'growp_if_login' );

/**
 * ログインしていないユーザだけに表示するショートコード
 * [if-not-login] このメッセージはログインしていないユーザだけに表示されます [/if-not-login]
 *
 * @param $atts
 * @param null $content
 *
 * @return string
 */
function growp_if_not_login( $atts, $content = null ) {
	if ( is_user_logged_in() ) {
		return '';
	} else {
		return '' . do_shortcode( $content ) . '';
	}
}

add_shortcode( 'if-not-login', 'growp_if_not_login' );



/**
 * ファビコンを統一
 * HTMLの画像ディレクトリを見て、favicon.png, webclip-icon.png, favicon.ico のいずれかが存在すればそれを返す
 */
add_filter( 'get_site_icon_url', function( $url, $size, $blog_id ){
	$filenames = [
		"favicon.png",
		"webclip-icon.png",
		"favicon.ico",
	];
	foreach ( $filenames as $filename ){
		if ( file_exists( get_stylesheet_directory() . "/assets/images/" . $filename ) ){
			return GUrl::asset() . "/assets/images/" . $filename;
		}
	}
    return $url;
}, 11, 3);


/**
 * 管理画面のテーマエディター、プラグインエディターが機能するように調整
 *
 * @param [type] $verify
 * @return void
 */
function growp_https_local_ssl_verify ($verify){
    if ( is_admin() && wp_doing_ajax() && isset($_REQUEST["action"]) && $_REQUEST["action"] === "edit-theme-plugin-file" ){
        return false;
    }
    return $verify;
}

add_filter( "https_local_ssl_verify", "growp_https_local_ssl_verify");
add_filter( "https_ssl_verify", "growp_https_local_ssl_verify");

function growp_fixed_admin_url( $url = "", $path = "" ){
    if ( is_admin() && is_multisite() &&
    ( strpos($url, "/wp-admin/theme-editor.php") !== false || strpos($url, "/wp-admin/plugin-editor.php") !== false ) ){
        $url = str_replace("/wp-admin/theme-editor.php", "/wp-admin/network/theme-editor.php", $url);
        $url = str_replace("/wp-admin/plugin-editor.php", "/wp-admin/network/plugin-editor.php", $url);
    }
    return $url;
}

add_filter( "admin_url", "growp_fixed_admin_url", 10, 2 );


/**
 * 最大執筆モードオフ
 *
 * @param $expand
 * @param $post_type
 * @return false
 */
function growp_editor_expand($expand, $post_type) {
	return false;
}
add_filter("wp_editor_expand", "growp_editor_expand",10, 2);

