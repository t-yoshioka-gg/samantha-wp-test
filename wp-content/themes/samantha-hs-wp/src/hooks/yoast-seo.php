<?php
/**
 * YoastSEO関連のフィルタ
 */

/**
 * パンくずを変更
 *
 * @param $links
 *
 * @return mixed
 */
function override_yoast_breadcrumb( $links ) {
	//個人の方
	$_posttypes = [
		'projects',
		'cases',
	];
	if ( is_post_type_archive( $_posttypes ) || is_singular( $_posttypes ) ) {
		$add_link = [];
		//$add_link[] = array('text' => '個人の方（ 顧問登録 ）', 'url' => '/candidates/');
		$add_link[] = array( 'text' => '顧問になる', 'url' => '/candidates/' );
		array_splice( $links, 1, 0, $add_link );

		return $links;
	}

	//法人の方
	$_posttypes_corp = [
		'supports',
	];
	if ( is_post_type_archive( $_posttypes_corp ) || is_singular( $_posttypes_corp ) ) {
		$add_link = [];
		//$add_link[] = array('text' => '法人の方-サービス概要', 'url' => '/corporations/');
		$add_link[] = array( 'text' => '顧問をつかう', 'url' => '/corporations/' );
		array_splice( $links, 1, 0, $add_link );

		return $links;
	}

	return $links;
}

//add_filter( 'wpseo_breadcrumb_links', 'override_yoast_breadcrumb' );


/**
 * titleタグへのフィルタ
 *
 * @param $title
 *
 * @return mixed|string
 */
function growp_seo_title( $title ) {
	//条件分岐
	if ( is_page( 'about' ) ) {
		return '会社概要';
	}

	return $title;
}

//add_filter( 'wpseo_title', 'growp_seo_title' );

/**
 * meta[content=description]へのフィルター
 *
 * @param $metadesc
 * @return mixed|string
 */
function growp_seo_metadesc( $metadesc ) {
	//条件分岐
	if ( is_page( 'about' ) ) {
		return '会社概要をご紹介いたします。';
	}

	return $metadesc;
}
//add_filter( 'wpseo_metadesc', 'growp_seo_metadesc' );


/**
 * meta[robots] のフィルター
 */
function growp_seo_robots($robots) {
	//条件分岐
	if ( is_single('test') ) {
		return 'noindex,nofollow';
	}
	return $robots;
}
//add_filter( 'wpseo_robots', 'growp_seo_robots' );


/**
 * OGPのサイト名箇所のフィルター
 */
function growp_og_site_name($site_name) {
	//条件分岐
	if ( is_page('about') ) {
		return '企業サイト';
	}
	return $site_name;
}
//add_filter( 'wpseo_opengraph_site_name', 'growp_og_site_name' );




//add_filter( "redirect_canonical", function ( $redirect_url ) {
//	if ( is_paged() ) {
//		return false;
//	}
//
//	return $redirect_url;
//} );


/**
 * セパレーターに縦棒を追加
 */
// add_filter("wpseo_separator_options", function ($separators){
// 	$separators[] = "|";
// 	return $separators;
// });



/**
 * デフォルトで追加される権限の削除
 */
add_action( "admin_init", function(){
	// `SEO Manager` 権限の削除
	if ( get_role('wpseo_manager') ) {
		remove_role( 'wpseo_manager' );
	}
	// `SEO Editor` 権限の削除
	if ( get_role('wpseo_editor') ) {
		remove_role( 'wpseo_editor' );
	}
} );

/**
 * https://gist.github.com/wpchannel/7cdd6eed0927ea5732d7
 * 管理者以外、「トラフィックを見逃していないか確認しましょう。」の通知を非表示
 */
function growp_remove_yoast_counters() {
	// If Yoast plugin active
	if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) && is_user_logged_in() && ! current_user_can( "administrator" ) ) {
		echo '<style>
	        #wpbody .yoast-notification.notice.notice-warning.is-dismissible {
	          display: none;
	        } 
        </style>';
	}
}

add_action( 'wp_head', 'growp_remove_yoast_counters' );
add_action( 'admin_head', 'growp_remove_yoast_counters' );

/**
 * パンくずリスト内のホワイトスペースを消す
 *
 * @param string $output
 *
 * @return string
 */
function growp_filter_wpseo_breadcrumb_output( string $output ): string {
	return str_replace( "> <", "><", $output );
}

add_filter( 'wpseo_breadcrumb_output', 'growp_filter_wpseo_breadcrumb_output', 10, 1 );
