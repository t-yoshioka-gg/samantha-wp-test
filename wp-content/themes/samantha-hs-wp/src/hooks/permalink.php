<?php

/**
 * タクソノミーアーカイブをIDで指定する
 */
function growp_change_term_archive_permalink() {
	/**
	 * 有効にするタクソノミーを指定
	 */
	$change_permalink_taxonomies = [
		'plan_style',
	];
	foreach ( $change_permalink_taxonomies as $change_permalink_taxonomy ) {
		// URLを取得
		add_filter( 'term_link', function ( $url, $term, $taxonomy ) use ( $change_permalink_taxonomy ) {
			global $wp_rewrite;
			// post_tag only
			if ( $taxonomy == $change_permalink_taxonomy ) {
				if ( $wp_rewrite->using_permalinks() ) {
					$permastruct = $wp_rewrite->get_extra_permastruct( $change_permalink_taxonomy );
					$permastruct = str_replace( '%' . $change_permalink_taxonomy . '%', $term->term_id, $permastruct );
					$url         = home_url( user_trailingslashit( $permastruct, $change_permalink_taxonomy ) );
				}
			}

			return $url;
		}, 10, 3 );


		add_filter( 'query_vars', function ( $vars ) use ( $change_permalink_taxonomy ) {
			$vars[] = $change_permalink_taxonomy;

			return $vars;
		} );


		add_action( 'parse_query', function ( $query ) use ( $change_permalink_taxonomy ) {
			if ( isset( $query->query_vars[ $change_permalink_taxonomy ] ) ):
				if ( $term = get_term_by( 'id', $query->query_vars[ $change_permalink_taxonomy ], $change_permalink_taxonomy ) ) {
					$query->query_vars[ $change_permalink_taxonomy ] = $term->slug;
				}
			endif;
		} );
	}
}

add_action( "init", "growp_change_term_archive_permalink" );


