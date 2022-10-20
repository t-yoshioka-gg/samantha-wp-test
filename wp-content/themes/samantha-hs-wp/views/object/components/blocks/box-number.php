<?php
$number_title = get_sub_field( 'number_title' );
$items        = get_sub_field( 'items' );
?>
<div class="l-container">
	<div class="c-box-number">
		<?php
		$count = 1;
		foreach ( $items as $item ) {
			?>
			<div class="c-box-number__block">
				<div class="c-box-number__head">
					<div class="c-box-number__number">
						<small><?php echo $number_title; ?></small>
						<span><?php echo sprintf( "%02d", $count ++ ); ?></span>
					</div>
				</div>
				<div class="c-box-number__content">
					<div class="c-box-number__title">
						<?php echo $item["title"]; ?>
					</div>
					<div class="c-box-number__text">
						<?php echo $item["text"]; ?>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>
