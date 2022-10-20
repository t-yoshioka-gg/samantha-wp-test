<?php
/**
 * Template Name: 【テンプレート】デザインページ
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * @see http://codex.wordpress.org/Template_Hierarchy
 * =====================================================
 */
while ( have_posts() ) :
	the_post();
	remove_filter( 'the_content', 'wpautop' );
	the_content();
	wp_link_pages( array(
		'before' => '<div class="page-links">' . __( 'Pages:', 'growp' ),
		'after'  => '</div>',
	) );
endwhile; // end of the loop.
