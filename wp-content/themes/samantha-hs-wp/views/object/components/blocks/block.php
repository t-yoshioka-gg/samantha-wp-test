<?php
$items = get_sub_field( 'items' );
if ( ! $items ) {
	return;
}
?>
<div class="l-container">

	<div class="c-block">
		<div class="c-block__blocks">
			<?php
			foreach ( $items as $item ) {
				$image_url = GTag::get_attachment_url( $item["image"] );
				?>
				<div class="c-block__block">
					<div class="row">
						<div class="large-4 small-12">
							<div class="c-block__image">
								<img src="<?php echo $image_url; ?>" alt="">
							</div>
						</div>
						<div class="large-8 small-12">
							<div class="c-block__content">
								<div class="c-block__title c-heading is-sm is-border-under is-bottom">
									<?php echo $item["title"]; ?>
								</div>
								<div class="c-block__text">
									<p><?php echo $item["text"]; ?></p>
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
</div>
