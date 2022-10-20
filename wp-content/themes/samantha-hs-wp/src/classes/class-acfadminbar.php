<?php

class GROWP_AcfAdminBar {

	public function __construct() {
		add_filter( "admin_bar_menu", [ $this, "adminbar" ], 90 );
		add_action( "wp_footer", function () {
			?>
			<script>
				(function ($) {
					$(function () {
						$("#wp-admin-bar-growp_acf > .ab-item").on("click",function (){
							$(this).next(".ab-sub-wrapper").toggle()
						})
					})
				})(jQuery)
			</script>
			<?php
		}, 90 );

	}

	public function adminbar( $wp_admin_bar ) {
		if ( is_user_logged_in() ) {
			$currentuser = wp_get_current_user();
			if ( $currentuser->has_cap( "administrator" ) ) {
				if ( ! get_the_ID() ) {
					return false;
				}
				$objects = get_field_objects( get_the_ID() );
				if ( ! $objects ) {
					return false;
				}

				$wp_admin_bar->add_node( [
					'id'    => 'growp_acf',
					'title' => '<span class="ab-icon dashicons dashicons-admin-post"></span>ACF',
				] );
				foreach ( $objects as $object ) {
					$args = array(
						'id'     => $object["key"],
						'title'  => $object["label"] . " : <input id='" . $object["key"] . "' class='copybtn' data-clipboard-target='#" . $object["key"] . "' readonly type='text' style='-webkit-appearance: none; background: transparent; border: none; padding: 0; color: #fff;' value='" . $object["name"] . "' />",
						'parent' => 'growp_acf',
					);
					$wp_admin_bar->add_node( $args );

					$args = array(
						'id'     => $object["key"] . "_type",
						'title'  => 'タイプ : ' . $object["type"] . '',
						'parent' => $object["key"],
					);
					$wp_admin_bar->add_node( $args );
					$args = array(
						'id'     => $object["key"] . "_code",
						'title'  => 'the_field(\'' . $object["name"] . '\');',
						'parent' => $object["key"],
					);
					$wp_admin_bar->add_node( $args );
					$args = array(
						'id'     => $object["key"] . "_code2",
						'title'  => "$" . $object["name"] . ' = get_field(\'' . $object["name"] . '\');',
						'parent' => $object["key"],
					);
					$wp_admin_bar->add_node( $args );

					if ( isset( $object["sub_fields"] ) ) {
						$args = array(
							'id'     => $object["key"] . "_sub",
							'title'  => 'サブフィールド',
							'parent' => $object["key"],
						);
						$wp_admin_bar->add_node( $args );

						foreach ( $object["sub_fields"] as $sub ) {
							$args = array(
								'id'     => $sub["key"],
								'title'  => $sub["label"] . " : <input disabled type='text' style='-webkit-appearance: none; background: transparent; border: none; padding: 0; color: #fff;' value='" . $sub["name"] . "' />",
								'parent' => $object["key"] . "_sub",
							);
							$wp_admin_bar->add_node( $args );
						}
					}
				}
			}
		}
	}
}

add_action( "init", function () {
	new GROWP_AcfAdminBar();
} );
