<?php
/**
 * Setup script for this theme
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================''
 */

/**
 * テーマのセットアップ
 * @return void
 */


function growp_setup() {

	// 重大なエラー時のメール送信を停止
	add_filter( 'wp_fatal_error_handler_enabled', '__return_false' );

	load_theme_textdomain( 'growp', get_template_directory() . '/languages' );
	load_theme_textdomain( 'tgmpa', get_template_directory() . '/languages' );

	// automatic feed をサポート
	add_theme_support( 'automatic-feed-links' );

	// パンくず をサポート
	add_theme_support( 'growp-breadcrumbs' );

	// ページネーション をサポート
	add_theme_support( 'growp-pagination' );

	// アイキャッチ画像のサポート
	add_theme_support( 'post-thumbnails' );

	// メニューのサポート
	add_theme_support( 'menus' );

	// タイトルタグをサポート
	add_theme_support( 'title-tag' );


	// HTML5構造化マークアップで出力
	add_theme_support(
		'html5',
		array(
			'comment-list',
			'search-form',
			'comment-form',
			'gallery',
			'caption',
		)
	);

	// editor-style を登録
	add_editor_style( [
		GROWP_STYLESHEET_URL,
		get_template_directory_uri() . "/overwrite.css",
	] );

	add_filter( 'growp_asset_url', function ( $url ) {
		return $url . '?ver=' . GROWP_VERSIONING;
	} );
}

add_action( 'after_setup_theme', 'growp_setup' );

/**
 * wp_head() で出力されるタグの調整
 *
 * @return void
 */
function growp_head_cleanup() {

	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );

	global $wp_widget_factory;

	remove_action( 'wp_head',
		array(
			$wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
			'recent_comments_style',
		)
	);
}

add_filter( 'init', 'growp_head_cleanup', 10 );


/**
 * 著者一覧を表示しない
 */
function growp_protect_authorpage() {
	if ( is_author() ) {
		wp_redirect( home_url( '/' ) );
		exit;
	}
}

add_action( 'template_redirect', 'growp_protect_authorpage' );


// 登録のサンプル
function growp_register_menus() {
	new GROWP_MenuPosts( 'global_nav', 'グローバルナビゲーション' );
}

add_action( "registered_taxonomy", "growp_register_menus" );


/**
 * 編集者の権限を変更し、ユーザーを追加することができるように
 */
function growp_add_editor_roles() {
	$role = get_role( 'editor' );
	$role->add_cap( 'delete_users' );
	$role->add_cap( 'create_users' );
	$role->add_cap( 'remove_users' );
	$role->add_cap( 'edit_users' );
	$role->add_cap( 'edit_user' );
	$role->add_cap( 'promote_users' );
	$role->add_cap( 'promote_user' );
	$role->add_cap( 'list_users' );
	$role->add_cap( 'unfiltered_html' ); // マルチサイトでHTMLが消えることを防ぐ
	$adminrole = get_role( 'administrator' ); // マルチサイトでHTMLが消えることを防ぐ
	$adminrole->add_cap( 'unfiltered_html' ); // マルチサイトでHTMLが消えることを防ぐ
}

add_action( 'admin_init', 'growp_add_editor_roles' );

/**
 * 管理者以外は新規ユーザー登録で管理者権限アカウントを追加できないように
 */
function growp_filter_editable_roles( $all_roles ) {

	$current_user = wp_get_current_user();
	if ( isset( $current_user->roles[0] ) && $current_user->roles[0] !== "administrator" ) {
		unset( $all_roles["administrator"] );
	}

	return $all_roles;
}

add_filter( "editable_roles", "growp_filter_editable_roles" );

/**
 * GrowGroupユーザーは削除されないように
 * @param $user_id
 * @return false
 */
function block_delete_user_growgroup($user_id) {
    if ( $user_id === 1) {
        wp_safe_redirect('/wp-admin/users.php', 302);
        exit();
    }
}
add_action( 'delete_user', 'block_delete_user_growgroup' );

/**
 * TinyMCEのspanタグ等の自動削除を停止
 */
function growp_override_mce_options( $init_array ) {
	global $allowedposttags;

	$init_array['valid_elements']          = '*[*]';
	$init_array['extended_valid_elements'] = '*[*]';
	$init_array['valid_children']          = '+a[' . implode( '|', array_keys( $allowedposttags ) ) . '|link|meta|style|script]';
	$init_array['valid_children']          .= ',+body[' . implode( '|', array_keys( $allowedposttags ) ) . '|link|meta|style|script]';
	$init_array['valid_children']          .= ',+div[' . implode( '|', array_keys( $allowedposttags ) ) . '|link|meta|style|script]';
	$init_array['valid_children']          .= ',+span[' . implode( '|', array_keys( $allowedposttags ) ) . '|link|meta|style|script]';
	$init_array['indent']                  = true;
	$init_array['wpautop']                 = false;
	$init_array['force_p_newlines']        = false;
	$init_array['block_formats']           = '段落=p;見出し2=h2;見出し3=h3;見出し4=h4;見出し5=h5;見出し6=h6;整形済みテキスト=pre;';


	return $init_array;
}

add_filter( 'tiny_mce_before_init', 'growp_override_mce_options' );

/**
 * 保存時のiframe等の自動削除を停止
 *
 * @param $content
 *
 * @return mixed
 */
function growp_content_save_pre( $content ) {
	global $allowedposttags;

	// iframeとiframeで使える属性を指定する
	$allowedposttags['iframe'] = array(
		'class'        => array(),
		'src'          => array(),
		'width'        => array(),
		'height'       => array(),
		'frameborder'  => array(),
		'scrolling'    => array(),
		'marginheight' => array(),
		'marginwidth'  => array(),
		'style'        => array()
	);
	$allowedposttags['script'] = array(
		'async'        => array(),
		'class'        => array(),
		'src'          => array(),
		'charset'      => array(),
		'width'        => array(),
		'height'       => array(),
		'frameborder'  => array(),
		'scrolling'    => array(),
		'marginheight' => array(),
		'marginwidth'  => array(),
		'style'        => array()
	);

	return $content;
}

add_filter( 'content_save_pre', 'growp_content_save_pre' );


function growp_kses_allowed_html( $allowedposttags ) {

	$allowed                     = array(
		'src'                   => true,
		'width'                 => true,
		'height'                => true,
		'id'                    => true,
		'class'                 => true,
		'frameborder'           => true,
		'webkitAllowFullScreen' => true,
		'mozallowfullscreen'    => true,
		'allowFullScreen'       => true,
		'async'                 => true,
		'action'                => true,
		'charset'               => true,
		'scrolling'             => true,
		'marginheight'          => true,
		'marginwidth'           => true,
		'style'                 => true,
		'placeholder'           => true,
		'type'                  => true,
		'name'                  => true,
		'onclick'               => true,
		'defer'                 => true,
		'xmlns'                 => true,
		'viewbox'               => true,
		'data-name'             => true,
		'transform'             => true,
		'd'                     => true,
		'stroke-width'          => true,
		'stroke'                => true,
		'data-name'             => true,
	);
	$allowedposttags['noscript'] = $allowed;
	$allowedposttags['script']   = $allowed;
	$allowedposttags['iframe']   = $allowed;
	$allowedposttags['input']    = $allowed;
	$allowedposttags['textarea'] = $allowed;
	$allowedposttags['select']   = $allowed;
	$allowedposttags['option']   = $allowed;
	$allowedposttags['form']     = $allowed;
	$allowedposttags['button']   = $allowed;
	$allowedposttags['script']   = $allowed;
	$allowedposttags['style']    = $allowed;
	$allowedposttags['canvas']   = $allowed;
	$allowedposttags['path']     = $allowed;

	return $allowedposttags;

}

add_filter( 'wp_kses_allowed_html', 'growp_kses_allowed_html', 1 );


/**
 * ページヘッダーのフィルター
 *
 * @param $init_array
 */
function growp_page_headers( $pageheaders ) {
	// ACF での更新をサポートする際
	// if ( is_page() ) {
	// 	$_pageheaders            = array(
	// 		'title'    => get_field( "title", get_the_ID() ),
	// 		'subtitle' => get_field( "subtitle", get_the_ID() ),
	// 		'image'    => get_field( "image", get_the_ID() ),
	// 	);
	// 	$pageheaders["title"]    = ( isset( $_pageheaders["title"] ) && $_pageheaders["title"] ) ? $_pageheaders["title"] : $pageheaders["title"];
	// 	$pageheaders["subtitle"] = ( isset( $_pageheaders["subtitle"] ) && $_pageheaders["subtitle"] ) ? $_pageheaders["subtitle"] : $pageheaders["subtitle"];
	// 	$pageheaders["image"]    = ( isset( $_pageheaders["image"] ) && $_pageheaders["image"] ) ? wp_get_attachment_image_url( $_pageheaders["image"], 'full' ) : $pageheaders["image"];
	// }
	return $pageheaders;
}

add_filter( "growp/page_header", 'growp_page_headers' );

function growp_icpo_admin_style() {
	?>
    <style>#the-list .ui-sortable-placeholder {
            display: none;
        }</style>
	<?php
}

add_action( 'admin_head', 'growp_icpo_admin_style' );


add_action( 'admin_footer', 'mw_wp_form_format_mail' );

function mw_wp_form_format_mail() {
	$screen = get_current_screen();
	if ( $screen->id === "mw-wp-form" && $screen->base === "post" ) {
		?>
        <script type="html/template" id="mw_wp_form_copymailtext">
            <div id="mw-wp-form_copymailtext" class="postbox">
                <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">パネルを閉じる: アドオン</span><span class="toggle-indicator" aria-hidden="true"></span></button>
                <h2 class="hndle ui-sortable-handle"><span>メール内容</span></h2>
                <div class="inside">
                    <p>現在のフォームからメール返信文の一部を自動生成します。</p>
                    <p>
                        <textarea name="copymailtext" id="" cols="32" rows="10"></textarea>
                        <a href="#" class="js-copymailtext button button-primary">メール文面を生成</a>
                    </p>
                </div>
            </div>
        </script>
        <script>
            (function ($) {
                var GenerateMWWPFormMailText = function () {
                    return this;
                };
                GenerateMWWPFormMailText.prototype = {
                    init: function () {
                        $("#mw-wp-form_addon").before($("#mw_wp_form_copymailtext").html());
                        this.generate();
                    },
                    generate: function () {
                        var $form = $("#content");
                        var form_html = $form.val();
                        var lines = form_html.match(/\[mwform.*?name=\"(.*?)\"/gi);
                        var $names = ["【 お問い合わせ内容 】"];
                        var _list = [];
                        for (var i = 0; i < lines.length; i++) {
                            var line = lines[i].match(/\[mwform.*?name=\"(.*?)\"/i);
                            if (line[1] === "submit") {
                                continue;
                            }
                            if (line[1] === "個人情報の取り扱いについて同意する") {
                                continue;
                            }
                            if (line[1] === "個人情報保護方針について同意する") {
                                continue;
                            }
                            var _name = line[1];
                            $names.push('');
                            $names.push('[' + _name + ']');
                            $names.push('{' + _name + '}');
                        }
                        var $textarea = $("textarea[name=copymailtext]");
                        $textarea.val($names.join("\n"));
                        $textarea.html($names.join("\n"));
                    },
                    trigger: function () {
                        var self = this;
                        $(".js-copymailtext").on("click", function (e) {
                            e.preventDefault();
                            self.generate();
                            $("#mw-wp-form_validation .add-btn").click();
                        });
                    }
                }
                window.GenerateMWWPFormMailTextObject = new GenerateMWWPFormMailText();
                GenerateMWWPFormMailTextObject.init();
            })(jQuery)
        </script>
		<?php
	}
}


/**
 * 固定ページの親ページとして下書き、非公開の記事も参照できるように
 */
add_filter( 'page_attributes_dropdown_pages_args', function ( $dropdown_args ) {
	$dropdown_args['post_status'] = array( 'publish', 'draft', 'private' );

	return $dropdown_args;
}, 1, 1 );

// 【WordPress】更新・バージョンアップの停止、無効化
// 管理者以外
add_action( "init", function () {
	if ( current_user_can( "administrator" ) ) {
		return;
	}
	// コア
	add_filter( 'pre_site_transient_update_core', '__return_zero' );
	remove_action( 'wp_version_check', 'wp_version_check' );
	remove_action( 'admin_init', '_maybe_update_core' );

	// プラグイン
	remove_action( 'load-update-core.php', 'wp_update_plugins' );
	add_filter( 'pre_site_transient_update_plugins', "__return_false" );

	// テーマ
	remove_action( 'load-update-core.php', 'wp_update_themes' );
	add_filter( 'pre_site_transient_update_themes', "__return_false" );

} );


/**
 * 記事スラッグに日本語が含まれていたら自動的に英語に変換
 * https://www.warna.info/archives/2317/comment-page-1/
 */
add_filter( 'wp_unique_post_slug', 'growp_unique_post_slug', 10, 4 );
function growp_unique_post_slug( $slug, $post_ID, $post_status, $post_type ) {
	if ( preg_match( '/(%[0-9a-f]{2})+/', $slug ) ) {
		$slug = utf8_uri_encode( $post_type ) . '-' . $post_ID;
	}

	return $slug;
}

/**
 * カテゴリ・タクソノミー等のスラッグに日本語が含まれていたら自動的に英語に変換
 * https://www.nxworld.net/wp-create-taxonomies-auto-slug.html
 */
add_action( 'create_category', 'growp_post_taxonomy_auto_slug', 10 );
add_action( 'create_post_tag', 'growp_post_taxonomy_auto_slug', 10 );
//add_action( 'create_works_cat', 'growp_post_taxonomy_auto_slug', 10 ); // 必要に応じてカスタムタクソノミーを追加
function growp_post_taxonomy_auto_slug( $term_id ) {
	$tax  = str_replace( 'create_', '', current_filter() );
	$term = get_term( $term_id, $tax );
	if ( preg_match( '/(%[0-9a-f]{2})+/', $term->slug ) ) {
		$args = array(
			'slug' => $term->taxonomy . '-' . $term->term_id
		);
		wp_update_term( $term_id, $tax, $args );
	}
}


/**
 * Yoast SEOにて固定ページのデフォルトのOGPを変更
 */
add_filter( 'wpseo_opengraph_image', function ( $img ) {
	if ( is_singular() && ! get_post_meta( get_the_ID(), "_yoast_wpseo_opengraph-image", true ) ) {
		$s = get_option( "wpseo_social" );
		$s = maybe_unserialize( $s );

		return $s["og_default_image"];
	}

	return $img;
} );

/**
 * acf のsave_jsonを有効に
 */
add_filter( 'acf/settings/save_json', function ( $path ) {
	$path = __DIR__ . '/json';

	return $path;
} );
add_filter( 'acf/settings/load_json', function ( $paths ) {
	unset( $paths[0] );
	$paths[] = __DIR__ . '/json';

	return $paths;
} );


/**
 * yoast seo のnoticeを非表示に
 */
add_action( 'admin_init', function () {
	if ( class_exists( 'Yoast_Notification_Center' ) ) {
		$yoast_nc = Yoast_Notification_Center::get();
		remove_action( 'admin_notices', array( $yoast_nc, 'display_notifications' ) );
		remove_action( 'all_admin_notices', array( $yoast_nc, 'display_notifications' ) );
	}
} );

/**
 * マルチサイト上で権限[ 管理者/編集者/投稿者 ]に対してHTMLタグ利用を許可
 */
add_filter( 'map_meta_cap', 'growp_add_map_meta_cap', 1, 3 );
function growp_add_map_meta_cap( $caps, $cap, $user_id ) {
	if ( $cap === 'unfiltered_html' ) {
		if ( user_can( $user_id, 'administrator' ) || user_can( $user_id, 'editor' ) || user_can( $user_id, 'author' ) ) {
			$caps = array( 'unfiltered_html' );
		}
	}

	return $caps;
}

/**
 *
 * the_content、ACFでのthe_contentでショートコード付近のhtmlタグを除去する
 *
 * @param string $content
 *
 * @return string
 */
function growp_protect_shortcode( $content ) {
	$content = str_replace( '<p>[', '<div>[', $content );
	$content = str_replace( ']</p>', ']</div>', $content );
	$content = str_replace( ']<br>', ']', $content );
	$content = str_replace( ']<br />', ']', $content );

	return $content;
}

add_filter( 'the_editor_content', 'growp_protect_shortcode' );
add_filter( 'the_content', 'growp_protect_shortcode' );
add_filter( 'acf_the_editor_content', 'growp_protect_shortcode' );
add_filter( 'acf_the_content', 'growp_protect_shortcode' );


/**
 *
 * MIMEタイプの追加
 *
 * @param array $mimes_types
 *
 * @return array
 */
function growp_modify_upload_mimes( array $mimes_types ): array {
	// add your extension to the mimes array as below
	$mimes_types['zip'] = 'application/zip';
	$mimes_types['gz']  = 'application/x-gzip';

	return $mimes_types;
}

//add_filter( 'upload_mimes', 'growp_modify_upload_mimes', 99 );

/**
 *
 * zip形式のアップロードを許可する
 *
 * @param array $types
 * @param string $file
 * @param string $filename
 * @param string|array $mimes
 *
 * @return array
 */
function growp_add_allow_upload_extension_exception( $types, $file, $filename, $mimes ) {
	// Do basic extension validation and MIME mapping
	$wp_filetype = wp_check_filetype( $filename, $mimes );
	$ext         = $wp_filetype['ext'];
	$type        = $wp_filetype['type'];
	if ( in_array( $ext, array( 'zip', 'gz' ) ) ) { // it allows zip files
		$types['ext']  = $ext;
		$types['type'] = $type;
	}

	return $types;
}

//add_filter( 'wp_check_filetype_and_ext', 'growp_add_allow_upload_extension_exception', 99, 4 );


