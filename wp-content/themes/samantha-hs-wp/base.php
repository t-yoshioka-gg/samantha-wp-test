<?php
/**
 * ベーステンプレート
 * : テンプレート階層を上書きし、
 * 基本的にこのテンプレートを先に読み込みます。
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */

$content = gm_template::get_content();

// 固定ページをエディタに保存する際はコメントアウトを解除
// gm_template::save_content();

GTemplate::get_template( "foundation/head" );
GTemplate::get_layout( "header" );
//GTemplate::get_layout( "global-nav" );
GTemplate::get_component( "mainvisual" );
GTemplate::get_component( "page-header" );
$wrapper = apply_filters( 'growp/wrapper', 'onecolumn' );

// 1カラム用
if ( $wrapper === "onecolumn" ) {
	?>
    <main class="l-main">
		<?php
		echo $content;
		unset( $content );
		?>
    </main>
	<?php
// 2カラム用
} else {
	?>
    <main class="l-main is-two-columns">
        <div class="l-container">
            <div class="l-wrapper">
				<?php
				echo $content;
				unset( $content );
				?>
            </div>
            <aside class="l-aside" data-sticky-container>
				<?php
				// サイドバー
				GTemplate::get_layout( "sidebar" );
				?>
            </aside>
        </div>
    </main>
	<?php
}

// フッター取得前のアクションフック
do_action( 'get_footer' );
// フッターを取得
GTemplate::get_layout( "footer" );
wp_footer(); ?>

<?php
// 管理画面＞サイトオプションより設定
echo do_shortcode( get_field( "o_body_append", "option" ) );
?>
</body>
</html>
