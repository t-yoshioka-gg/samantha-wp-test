<?php
/**
 * 固定ページテンプレート
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * @see http://codex.wordpress.org/Template_Hierarchy
 * =====================================================
 */
$page_object = gm_page::from_global();
the_post();
?>
<div class="l-section">
	<div class="l-container">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page' ); ?>>
			<div class="l-post-content">
				<?php
				echo $page_object->get_content();
				?>
			</div>
		</article>
	</div>
</div>
