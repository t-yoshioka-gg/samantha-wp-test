<?php
$items = get_sub_field( 'items' );
if ( ! $items ) {
	return;
}
?>

<div class="c-hero-block">
	<?php
	foreach ( $items as $item ) {
		$image_url = GTag::get_attachment_url( $item["image"] );
		?>

		<div class="c-hero-block__block">
			<div class="c-hero-block__image">
				<img src="<?php echo $image_url; ?>" alt="">
			</div>
			<div class="l-container">
				<div class="c-hero-block__content">
					<div class="c-hero-block__title  c-heading is-sm is-bottom">
						<?php echo $item["title"]; ?>
					</div>
					<p>
						<?php echo $item["text"]; ?>
					</p>
					<?php
					if ( isset( $item["link"]["url"] ) && isset( $item["link"]["text"] )
					     && $item["link"]["url"] && $item["link"]["text"] ) {
						?>
						<div class="c-hero-block__button">
							<a href="<?php echo $item["link"]["url"] ?>" class="c-button is-sm" target="<?php echo $item["link"]["target"] ?>">
								<?php echo $item["link"]["text"] ?>
							</a>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}
	?>
</div>
