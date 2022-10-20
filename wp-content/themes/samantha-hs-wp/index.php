<?php
/**
 * メインインデックステンプレート
 * =====================================================
 * @package  growp
 * @since 1.0.0
 * =====================================================
 */

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		GTemplate::get_project( "post-item" );
	}
	GNav::the_paging_nav();
} else {
	GTemplate::get_project( "not-found" );
}
