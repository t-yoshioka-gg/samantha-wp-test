<?php
/**
 * TGM Plugin Activation
 * 必要なプラグインのインストールと有効化
 *
 * @see http://tgmpluginactivation.com/
 */

//if ( class_exists( 'TGM_Plugin_Activation' ) ) {
//	add_action( 'tgmpa_register', 'growp_theme_register_required_plugins' );
//
//	function growp_theme_register_required_plugins() {
//
//		$plugins = array(
//
//			array(
//				'name'             => 'Yoast SEO',
//				'slug'             => 'wordpress-seo',
//				'required'         => true,
//				'force_activation' => false,
//			),
//
//			array(
//				'name'             => 'MW WP Form',
//				'slug'             => 'mw-wp-form',
//				'required'         => true,
//				'force_activation' => false,
//			),
//			array(
//				'name'             => 'WP Migrate db',
//				'slug'             => 'wp-migrate-db',
//				'required'         => true,
//				'force_activation' => false,
//			),
//			array(
//				'name'             => 'Duplicate Post',
//				'slug'             => 'duplicate-post',
//				'required'         => true,
//				'force_activation' => false,
//			),
//
//			array(
//				'name'             => 'Custom Post Type Permalinks',
//				'slug'             => 'custom-post-type-permalinks',
//				'required'         => true,
//				'force_activation' => false,
//			),
//
//			array(
//				'name'             => 'Custom Post Type UI',
//				'slug'             => 'custom-post-type-ui',
//				'required'         => true,
//				'force_activation' => false,
//			),
//
//			array(
//				'name'             => 'Intuitive Custom Post Order',
//				'slug'             => 'intuitive-custom-post-order',
//				'required'         => true,
//				'force_activation' => false,
//			),
//
//			array(
//				'name'             => 'TinyMCE Advanced',
//				'slug'             => 'tinymce-advanced',
//				'required'         => true,
//				'force_activation' => false,
//			),
//
//			array(
//				'name'             => 'WP Admin UI Customize',
//				'slug'             => 'wp-admin-ui-customize',
//				'required'         => false,
//				'force_activation' => false,
//			),
//
//			array(
//				'name'             => 'SiteGuard WP Plugin',
//				'slug'             => 'siteguard',
//				'required'         => false,
//				'force_activation' => false,
//			),
//
//			array(
//				'name'             => 'Login rebuilder',
//				'slug'             => 'login-rebuilder',
//				'required'         => false,
//				'force_activation' => false,
//			),
//
//			array(
//				'name'             => 'GAinWP Google Analytics Integration for WordPress',
//				'slug'             => 'ga-in',
//				'required'         => false,
//				'force_activation' => false,
//			),
//
//			array(
//				'name'             => 'Classic Editor',
//				'slug'             => 'classic-editor',
//				'required'         => false,
//				'force_activation' => false,
//			),
//
//			array(
//				'name'             => 'Admin Columns',
//				'slug'             => 'codepress-admin-columns',
//				'required'         => false,
//				'force_activation' => false,
//			),
//			array(
//				'name'     => 'Advanced Custom Fields Pro',
//				'slug'     => 'advanced-custom-fields-pro',
//				'required' => false,
//				'source'   => 'https://github.com/wp-premium/advanced-custom-fields-pro/archive/master.zip',
//			),
//
//
//		);
//
//		/*
//		 * Array of configuration settings. Amend each line as needed.
//		 *
//		 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
//		 * strings available, please help us make TGMPA even better by giving us access to these translations or by
//		 * sending in a pull-request with .po file(s) with the translations.
//		 *
//		 * Only uncomment the strings in the config array if you want to customize the strings.
//		 */
//		/*
//		 * Array of configuration settings. Amend each line as needed.
//		 *
//		 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
//		 * strings available, please help us make TGMPA even better by giving us access to these translations or by
//		 * sending in a pull-request with .po file(s) with the translations.
//		 *
//		 * Only uncomment the strings in the config array if you want to customize the strings.
//		 */
//		$config = array(
//			'id'           => 'tgmpa',
//			// Unique ID for hashing notices for multiple instances of TGMPA.
//			'default_path' => '',
//			// Default absolute path to bundled plugins.
//			'menu'         => 'tgmpa-install-plugins',
//			// Menu slug.
//			'parent_slug'  => 'themes.php',
//			// Parent menu slug.
//			'capability'   => 'edit_theme_options',
//			// Capability needed to view plugin install page, should be a capability associated with the parent menu used.
//			'has_notices'  => true,
//			// Show admin notices or not.
//			'dismissable'  => true,
//			// If false, a user cannot dismiss the nag message.
//			'dismiss_msg'  => __( 'Dismiss this notice', 'growp' ),
//			// If 'dismissable' is false, this message will be output at top of nag.
//			'is_automatic' => true,
//			// Automatically activate plugins after installation or not.
//			'message'      => '',
//			// Message to output right before the plugins table.
//			'strings'      => array(
//				'page_title'                      => __( 'Install Required Plugins', 'growp' ),
//				'menu_title'                      => __( 'Install Plugins', 'growp' ),
//				'installing'                      => __( 'Installing Plugin: %s', 'growp' ),
//				// %s = plugin name.
//				'oops'                            => __( 'Something went wrong with the plugin API.', 'growp' ),
//				'notice_can_install_required'     => _n_noop(
//					'This theme requires the following plugin: %1$s.',
//					'This theme requires the following plugins: %1$s.',
//					'growp'
//				),
//				// %1$s = plugin name(s).
//				'notice_can_install_recommended'  => _n_noop(
//					'This theme recommends the following plugin: %1$s.',
//					'This theme recommends the following plugins: %1$s.',
//					'growp'
//				),
//				// %1$s = plugin name(s).
//				'notice_cannot_install'           => _n_noop(
//					'Sorry, but you do not have the correct permissions to install the %1$s plugin.',
//					'Sorry, but you do not have the correct permissions to install the %1$s plugins.',
//					'growp'
//				),
//				// %1$s = plugin name(s).
//				'notice_ask_to_update'            => _n_noop(
//					'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
//					'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
//					'growp'
//				),
//				// %1$s = plugin name(s).
//				'notice_ask_to_update_maybe'      => _n_noop(
//					'There is an update available for: %1$s.',
//					'There are updates available for the following plugins: %1$s.',
//					'growp'
//				),
//				// %1$s = plugin name(s).
//				'notice_cannot_update'            => _n_noop(
//					'Sorry, but you do not have the correct permissions to update the %1$s plugin.',
//					'Sorry, but you do not have the correct permissions to update the %1$s plugins.',
//					'growp'
//				),
//				// %1$s = plugin name(s).
//				'notice_can_activate_required'    => _n_noop(
//					'The following required plugin is currently inactive: %1$s.',
//					'The following required plugins are currently inactive: %1$s.',
//					'growp'
//				),
//				// %1$s = plugin name(s).
//				'notice_can_activate_recommended' => _n_noop(
//					'The following recommended plugin is currently inactive: %1$s.',
//					'The following recommended plugins are currently inactive: %1$s.',
//					'growp'
//				),
//				// %1$s = plugin name(s).
//				'notice_cannot_activate'          => _n_noop(
//					'Sorry, but you do not have the correct permissions to activate the %1$s plugin.',
//					'Sorry, but you do not have the correct permissions to activate the %1$s plugins.',
//					'growp'
//				),
//				// %1$s = plugin name(s).
//				'install_link'                    => _n_noop(
//					'Begin installing plugin',
//					'Begin installing plugins',
//					'growp'
//				),
//				'update_link'                     => _n_noop(
//					'Begin updating plugin',
//					'Begin updating plugins',
//					'growp'
//				),
//				'activate_link'                   => _n_noop(
//					'Begin activating plugin',
//					'Begin activating plugins',
//					'growp'
//				),
//				'return'                          => __( 'Return to Required Plugins Installer', 'growp' ),
//				'plugin_activated'                => __( 'Plugin activated successfully.', 'growp' ),
//				'activated_successfully'          => __( 'The following plugin was activated successfully:', 'growp' ),
//				'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'growp' ),
//				// %1$s = plugin name(s).
//				'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.',
//					'growp' ),
//				// %1$s = plugin name(s).
//				'complete'                        => __( 'All plugins installed and activated successfully. %1$s',
//					'growp' ),
//				// %s = dashboard link.
//				'contact_admin'                   => __( 'Please contact the administrator of this site for help.',
//					'tgmpa' ),
//
//				'nag_type' => 'updated',
//				// Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
//			),
//
//		);
//
//		tgmpa( $plugins, $config );
//	}
//
//}
