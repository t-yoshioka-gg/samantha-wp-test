<?php
/**
 * テーマ/管理画面で使用する js, cssの登録
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */

add_action( 'wp_enqueue_scripts', 'growp_scripts', 100 );

function growp_scripts() {
	/**
	 * 読み込むスタイルシートを定義
	 */
	$styles = array(
		// 子テーマの方のスタイルシートを読み込み
		array(
			'handle' => "main",
			'src'    => GROWP_STYLESHEET_URL,
			'deps'   => array(),
			'media'  => "all",
		),
		// app.css
		array(
			'handle' => "app",
			'src'    => get_theme_file_uri( "/assets/css/app.css" ),
			'deps'   => array(),
			'media'  => "all",
		),
		// 上書き用のスタイルシートを登録
		array(
			'handle' => "overwrite",
			'src'    => get_theme_file_uri( "/overwrite.css" ),
			'deps'   => array(),
			'media'  => "all",
		),
	);

	foreach ( $styles as $style_key => $style ) {
		$style = wp_parse_args( $style, array(
			'handle' => $style_key,
			"src"    => "",
			'deps'   => array(),
			'media'  => "all",
			'ver'    => GROWP_VERSIONING,
		) );
		extract( $style );
		wp_enqueue_style( "growp_" . $style['handle'], $style['src'], $style['deps'], $style['ver'], $style['media'] );
	}

	/**
	 * 読み込むJsファイルを定義
	 */
	$javascripts = array(

		// 子テーマのメインとなる app.js を登録
		array(
			'handle'    => "app", // ハンドル名
			'src'       => get_theme_file_uri( "/assets/js/app.js" ), // ファイルのURL
			'deps'      => array( "jquery" ), // 依存するスクリプトのハンドル名
			'in_footer' => true, // wp_footer に出力
		),

		// 追加用JavaScript
		array(
			'handle'    => "overwrite",
			'src'       => get_theme_file_uri( "/overwrite.js" ),
			'deps'      => array( "jquery" ),
			'in_footer' => true,
		),
	);

	foreach ( $javascripts as $js_key => $js ) {
		$js = wp_parse_args( $js, array(
			'handle'    => $js_key,
			'deps'      => array(),
			'media'     => "all",
			'in_footer' => true,
			'ver'       => GROWP_VERSIONING,
		) );

		wp_enqueue_script( "growp_" . $js['handle'], $js['src'], $js['deps'], $js['ver'], $js['in_footer'] );
	}

	/**
	 * コメント欄が有効なページでは、
	 * 返信用のjsを登録
	 */
	if ( is_single() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}



add_action( 'admin_enqueue_scripts', function () {
	wp_enqueue_style( 'growp_admin_style', get_template_directory_uri() . '/assets/css/admin-editor.css?ver=' . GROWP_VERSIONING );
} );

/**
 * TOC singleページ以外、関連するjs/cssの読み込みを停止する
 */
function growp_deregister_toc() {
	if ( ! class_exists( 'TOC_Plus' ) ) {
		return false;
	}
	$args       = [
		'public'   => true,
		'_builtin' => false
	];
	$post_types = get_post_types( $args );
	$post_types = array_merge( [ 'post' ], array_values( $post_types ) );
	if ( ! is_singular( $post_types ) ) {
		wp_deregister_script( 'toc-front' );
		wp_deregister_style( 'toc-screen' );
	}
}

add_action( 'wp_enqueue_scripts', 'growp_deregister_toc' );
add_action( 'admin_enqueue_script', 'growp_deregister_toc' );

/**
 * ブロックエディタのwp-block-library-cssを削除する
 */
function growp_dequeue_plugins_style() {
	//プラグインIDを指定し解除する
	wp_dequeue_style( 'wp-block-library' );
}

add_action( 'wp_enqueue_scripts', 'growp_dequeue_plugins_style', 9999 );
