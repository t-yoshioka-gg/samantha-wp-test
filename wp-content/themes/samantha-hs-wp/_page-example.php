<?php
/**
 * Template Name: サンプルページ
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * @see http://codex.wordpress.org/Template_Hierarchy
 * =====================================================
 */

// ページヘッダーの設定
add_filter( 'growp/page_header/title', function () {
	return 'テスト';
} );
add_filter( 'growp/page_header/subtitle', function () {
	return 'sub title';
} );
add_filter( 'growp/page_header/image', function () {
	return GUrl::asset( '/assets/images/exmaple01.jpg' );
} );

// GTemplateで呼び出すテンプレートを無効化
add_filter( 'growp/template/filepath', function ( $file ) {
	if ( $file === 'object/components/page-header' ) {
		return false;
	}
	return $file;
} );

the_post();
$page_object = gm_page::from_global();
?>
<div class="l-section">
	<div class="l-container">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page' ); ?>>
			<div class="l-post-content">
				<?php
				$page_object->get_content();
				?>
			</div>
		</article>
	</div>
</div>
