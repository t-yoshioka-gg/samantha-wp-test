<?php
/**
 * テーマのための関数
 * =====================================================
 * @package  growp
 * @since 1.0.0
 * =====================================================
 */

/**
 * バージョン情報の出力 / キャッシュ対策
 * 変更することで CSS, JavaScriptの末尾にバージョンを追加
 */
define( 'GROWP_VERSIONING', '1.0.0' );

// テンプレートのパス
define( 'GROWP_TEMPLATE_PATH', dirname( __FILE__ ) );

// CSSファイル
define( "GROWP_STYLESHEET_URL", get_stylesheet_directory_uri() . "/assets/css/style.css" );

// テーマのJavaScriptファイル
define( "GROWP_JAVASCRIPT_URL", get_stylesheet_directory_uri() . "/assets/js/scripts.js" );

// composer
require_once dirname( __FILE__ ) . "/vendor/autoload.php";

/**
 * テーマのための class
 */
require_once dirname( __FILE__ ) . "/src/classes/class-theme-wrapper.php";
require_once dirname( __FILE__ ) . "/src/classes/class-menu-posts.php";
require_once dirname( __FILE__ ) . "/src/classes/class-post-type.php";
require_once dirname( __FILE__ ) . "/src/classes/class-tgm-plugin-activation.php";
require_once dirname( __FILE__ ) . "/src/classes/class-walker-comment.php";
require_once dirname( __FILE__ ) . "/src/classes/class-walker-nav.php";
require_once dirname( __FILE__ ) . "/src/classes/class-sitemap.php";
require_once dirname( __FILE__ ) . "/src/classes/class-yoast-seo-index-clear.php";
require_once dirname( __FILE__ ) . "/src/classes/class-cache.php";
// require_once dirname( __FILE__ ) . "/src/classes/class-acfadminbar.php";

/**
 * base_post
 */
require_once dirname( __FILE__ ) . "/src/models/post_type/base_post.php";
require_once dirname( __FILE__ ) . "/src/models/taxonomy/base_term.php";

/**
 * src/controllers 内のファイルを読み込み
 */
foreach ( glob( dirname( __FILE__ ) . "/src/controllers/*.php" ) as $file ) {
	if ( file_exists( $file ) ) {
		require_once $file;
		$controller_class_name = "gm_" . str_replace( ".php", "", basename( $file ) );
		if ( $controller_class_name === "gm_base_controller" ) {
			continue;
		}
		$controller_class_name::get_instance();
	}
}

/**
 * src/models 内のファイルを読み込み
 * @todo タイミングみてnamespace等に切り替える
 */
foreach ( glob( dirname( __FILE__ ) . "/src/models/**/*.php" ) as $file ) {

	/**
	 * base_がファイル名に存在する場合は処理から除外する
	 */
	if ( mb_strpos( $file, "base_" ) !== false ){
		continue;
	}
	if ( file_exists( $file ) ) {
		require_once $file;
	}
}

foreach ( glob( dirname( __FILE__ ) . "/src/models/*.php" ) as $file ) {
	/**
	 * base_がファイル名に存在する場合は処理から除外する
	 */
	if ( mb_strpos( $file, "base_" ) !== false ){
		continue;
	}
	if ( file_exists( $file ) ) {
		require_once $file;
	}
}


// 開発ツール (不要な際はコメントアウト)
// require_once dirname( __FILE__ ) . "/src/classes/class-devtool.php";

/**
 * テンプレートタグ定義
 */
require_once dirname( __FILE__ ) . "/src/tags/nav.php";
require_once dirname( __FILE__ ) . "/src/tags/tag.php";
require_once dirname( __FILE__ ) . "/src/tags/template.php";
require_once dirname( __FILE__ ) . "/src/tags/url.php";

/**
 * アクションフック定義
 */
require_once dirname( __FILE__ ) . "/src/hooks/comment.php";
require_once dirname( __FILE__ ) . "/src/hooks/default-plugins.php";
require_once dirname( __FILE__ ) . "/src/hooks/extras.php";
require_once dirname( __FILE__ ) . "/src/hooks/scripts.php";
require_once dirname( __FILE__ ) . "/src/hooks/search.php";
require_once dirname( __FILE__ ) . "/src/hooks/setup.php";
require_once dirname( __FILE__ ) . "/src/hooks/sidebar.php";
require_once dirname( __FILE__ ) . "/src/hooks/admin-style.php";
require_once dirname( __FILE__ ) . "/src/hooks/yoast-seo.php";


require_once dirname( __FILE__ ) . "/src/hooks/mw-wp-form.php";
require_once dirname( __FILE__ ) . "/src/hooks/really-simple-csv-importer.php";


// テンプレートを固定ページとして作成
// growp-setup を利用した際に有効
function growp_create_pages() {
	if ( ! get_option( "growp_create_pages" ) ) {
		update_option( "growp_create_pages", true );
		$files = glob( __DIR__ . "/page-*.php" );
		foreach ( $files as $file ) {
			$fileheaders = get_file_data( $file, [ "Page Slug", "Template Name", "Page Template Name" ] );
			$post_id     = wp_insert_post( [
				'post_type'    => "page",
				'post_title'   => $fileheaders[1],
				'post_name'    => $fileheaders[0],
				'post_content' => "",
				'post_status'  => "publish",
			] );
			update_post_meta( $post_id, "_wp_page_template", $fileheaders[2] );
		}
	}
}

// add_action("init", "growp_create_pages");


/**
 * サイトオプション
 */
function growp_acf_op_init() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		$option_page = acf_add_options_page( array(
			'page_title' => "サイトオプション",
			'menu_title' => "サイトオプション",
			'menu_slug'  => 'theme-general-settings',
			'capability' => 'edit_posts',
			'redirect'   => false
		) );
	}
}

// ACF オプションページを利用する場合は以下のコメントアウトを外す
add_action( 'acf/init', 'growp_acf_op_init' );


/**
 * Webfont loader
 * Webフォントロード用のスクリプトを記述する
 */
function growp_fontloader() {
	?>
    <script>
		window.WebFontConfig = {
			// 以下にフォントを指定する
			custom: {
				urls: [
					"https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap",
					"https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap&text=0123456789",
					"https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&display=swap",
					"https://fonts.googleapis.com/css2?family=Zen+Old+Mincho:wght@400;700&display=swap",
					"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css",
				],
			},
			active: function () {
				sessionStorage.fonts = true;
			}
		};

		(function () {
			var wf = document.createElement('script');
			wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js';
			wf.type = 'text/javascript';
			wf.async = 'true';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(wf, s);
		})();
    </script>
	<?php
}

add_action( 'wp_head', 'growp_fontloader', 1 );

/**
 * 管理画面用の web font の import URL を記述
 */
function growp_editor_webfont() {
	// @TODO: 以下にGoogleFontsのインポート用URLを記述する
	$font_url = 'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&family=Roboto:wght@400;700&Libre+Baskerville:wght@400;700&Zen+Old+Mincho:wght@400;700&display=swap';
	add_editor_style( $font_url );
}

add_action( 'after_setup_theme', 'growp_editor_webfont', 1 );


if ( ! function_exists( "array_key_last" ) ) {
	function array_key_last( array $array ) {
		return key( array_slice( $array, - 1 ) );
	}
}


if( ! function_exists( 'log_it' ) ){
  /**
   * ログ出力用の関数
   * WP_DEBUG が true の場合 debug.log に出力する
   */
 function log_it( $message ) {
   if( WP_DEBUG === true ){
     if( is_array( $message ) || is_object( $message ) ){
       error_log( print_r( $message, true ) );
     } else {
       error_log( $message );
     }
   }
 }
}

/**
 * ACF Extended の UI 変更をオフ
 */
if( function_exists( 'acf_update_setting' ) ){
  add_action('acf/init', function (){
      // Disable Enhanced UI
      acf_update_setting('acfe/modules/ui', false);
  });
}


