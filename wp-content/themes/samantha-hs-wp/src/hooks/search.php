<?php
/**
 * 検索（search.php） 関連の設定
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */

/**
 * 検索結果から除外する
 * サイトオプションから設定
 *
 * @param $query
 */
function growp_search_exclusion_setting( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_search() ) {
		// サイトオプションにて設定
		$exclude_ids = [];

		$select_ids = get_field( "o_search_excludes", "option" );

		if ( $select_ids ) {
			$exclude_ids = $select_ids;
		}

		$mw_wp_forms = get_posts( [
			'posts_per_page' => - 1,
			'post_type'      => 'mw-wp-form'
		] );

		if ( isset( $mw_wp_forms ) ) {
			foreach ( $mw_wp_forms as $mw_wp_form ) {
				$_mw_metas = get_post_meta( $mw_wp_form->ID, 'mw-wp-form' );
				if ( isset( $_mw_metas ) ) {
					$confirm_post  = get_page_by_path( $_mw_metas[0]['confirmation_url'] );
					$complete_post = get_page_by_path( $_mw_metas[0]['complete_url'] );
					if ( isset( $confirm_post ) ) {
						array_push( $exclude_ids, $confirm_post->ID );
					}
					if ( isset( $complete_post ) ) {
						array_push( $exclude_ids, $complete_post->ID );
					}
				}
			}
		}

		if ( $exclude_ids ) {
			$query->set( 'post__not_in', $exclude_ids );
		}
	}
}

add_action( 'pre_get_posts', 'growp_search_exclusion_setting' );
