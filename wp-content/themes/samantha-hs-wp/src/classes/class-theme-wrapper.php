<?php
/**
 * テンプレート階層を書き換える
 *
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * @see http://scribu.net/wordpress/theme-wrappers.html
 * =====================================================
 */

/**
 * class Theme_Wrapper
 *
 */
class GROWP_Theme_Wrapper {
	/**
	 * Stores the full path to the main template file
	 */
	static $main_template;

	/**
	 * Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
	 */
	static $base;

	static function wrap( $template ) {

		/**
		 * 埋め込み除外
		 */
		if ( basename( $template ) === "embed.php" ) {
			$templates = [ 'views/embed/embed.php' ];
			return locate_template( $templates );
		}
		self::$main_template = $template;
		self::$base          = substr( basename( self::$main_template ), 0, - 4 );

		if ( 'index' == self::$base ) {
			self::$base = false;
		}

		$templates = array( 'base.php' );

		if ( self::$base ) {
			array_unshift( $templates, sprintf( 'base-%s.php', apply_filters( "growp_theme_wrapper_base", self::$base ) ) );

			return locate_template( $templates );

		} else {

			return locate_template( $templates );

		}
	}


}


add_filter( 'template_include', array( 'GROWP_Theme_Wrapper', 'wrap' ), 10, 1 );
