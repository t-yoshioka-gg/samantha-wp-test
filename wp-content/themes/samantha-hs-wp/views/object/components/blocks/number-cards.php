<?php
$items     = get_sub_field( 'items' );
$col_count = get_sub_field( 'number' );
if ( ! $items ) {
	return;
}
?>
<div class="l-container">
	<div class="row">
		<?php
		$col_class = "large-" . ( 12 / $col_count );
		foreach ( $items as $item ) {
			$image_url = GTag::get_attachment_url( $item["image"] );
			?>
			<div class="<?php echo $col_class; ?> small-12">
				<div class="c-card">
					<div class="c-card__block">
						<div class="c-card__image">
							<img src="<?php echo $image_url; ?>" alt="">
						</div>
						<div class="c-card__content">
							<div class="c-card__title">
								<?php echo $item["title"]; ?>
							</div>
							<div class="c-card__text">
								<?php echo $item["text"]; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>
