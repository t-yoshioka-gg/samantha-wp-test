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
	<div class="c-accordion">
		<?php
		foreach ( $items as $item ) {
			?>
			<div class="c-accordion__block  js-accordion <?php echo $block_class; ?>">
				<div class="c-accordion__head" data-accordion-title="accordion-title">
					<?php if ( ! empty( $head_icon ) ) {
						?>
						<div class="c-accordion__icon">
							<?php echo $head_icon; ?>
						</div>
						<?php
					}
					?>
					<div class="c-accordion__title">
						<?php echo $item["title"]; ?>
					</div>
				</div>
				<div class="c-accordion__content" data-accordion-content="accordion--text" <?php echo $content_style; ?>>
					<?php if ( ! empty( $body_icon ) ) {
						?>
						<div class="c-accordion__icon  is-color-accent">
							<?php echo $body_icon; ?>
						</div>
						<?php
					}
					?>
					<div class="c-accordion__text">
						<?php echo $item["text"]; ?>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>
