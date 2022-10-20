<?php
/**
 * Template Name: 自動整形なし
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * @see http://codex.wordpress.org/Template_Hierarchy
 * =====================================================
 */
the_post();
$page_object = gm_page::from_global();
remove_filter( 'the_content', 'wpautop' );
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
