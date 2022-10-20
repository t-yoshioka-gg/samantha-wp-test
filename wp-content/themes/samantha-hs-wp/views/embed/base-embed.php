<?php
/**
 * Contains the post embed base template
 *
 * When a post is embedded in an iframe, this file is used to create the output
 * if the active theme does not include an embed.php template.
 *
 * @package WordPress
 * @subpackage oEmbed
 * @since 4.4.0
 */
get_template_part( 'views/embed/header-embed' );

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		get_template_part( 'views/embed/content-embed' );
	endwhile;
else :
	get_template_part( 'views/embed', '404' );
endif;

get_template_part( 'views/embed/footer-embed' );
