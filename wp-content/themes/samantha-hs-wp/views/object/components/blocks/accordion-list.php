<?php
$items     = get_sub_field( 'items' );
$head_icon = get_sub_field( 'head_icon' );
$body_icon = get_sub_field( 'body_icon' );
$open      = get_sub_field( 'open' );

if ( ! $items ) {
	return;
}

$block_class   = "";
$content_style = "";
if ( $open == "open" ) {
	$block_class   = "is-open";
	$content_style = ' style="display: block;" ';
}

?>

<div class="l-container">
	<div class="c-accordion-list">
		<?php
		foreach ( $items as $item ) {
			?>
			<div class="c-accordion-list__block  js-accordion <?php echo $block_class; ?>">
				<div class="c-accordion-list__head" data-accordion-title="accordion-title">
					<?php echo $item["title"]; ?>
				</div>
				<div class="c-accordion-list__content" data-accordion-content="accordion--text" <?php echo $content_style; ?>>
					<?php echo $item["text"]; ?>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>
