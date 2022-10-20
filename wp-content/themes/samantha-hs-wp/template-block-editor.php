<?php
/**
 * Template Name: 【テンプレート】ブロック編集モード
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * @see http://codex.wordpress.org/Template_Hierarchy
 * =====================================================
 */
if ( ! have_rows( 'c_blocks' ) ) {
	return;
}
?>
<section class="l-section is-normal">
	<?php
	$count = 0;
	while ( have_rows( 'c_blocks' ) ) {
		the_row();
		$count ++;
		$settings = get_sub_field( "block_settings" );
		$class    = "l-block l-block-" . get_row_layout() . " " . $settings["margin"] . " " . $settings["class"];
		$class    = trim( $class );
		$style    = $settings["style"];
		$id       = "block-" . $count;

		if ( $settings["id"] ) {
			$id = $settings["id"];
		}

		echo "<section id='{$id}' class='{$class}' style='{$style}'>";
		GTemplate::get_block( get_row_layout() );
		echo "</section>";
	}
	?>
</section>

