<?php
$title        = get_sub_field( 'title' );
$text         = get_sub_field( 'text' );
$tel          = get_sub_field( 'tel' );
$tel_time     = get_sub_field( 'tel_time' );
$contact_url  = get_sub_field( 'contact_url' );
$contact_text = get_sub_field( 'contact_text' );
$image_id     = get_sub_field( 'image' );
$style        = "";

if ( ! $tel && ! $contact_url ) {
	return;
}

if ( $image_id ) {
	$style = ' style="background-image: url(' . GTag::get_attachment_url( $image_id ) . ')" ';
}

?>

<div class="l-offer" <?php echo $style;?>>
	<div class="l-container">
		<div class="l-offer__inner">
			<?php if ( $title ) { ?>
				<h2 class="c-heading is-md is-bottom"><?php echo $title; ?></h2>
			<?php } ?>
			<?php if ( $text ) { ?>
				<div class="l-offer__text"><?php echo $text; ?></div>
			<?php } ?>
			<div class="l-offer__items" >
				<div class="row" style="width:100%;">
					<div class="large-6 small-12">
						<a class="l-offer__button is-tel" href="tel:<?php echo $tel; ?>"><i class="fa fa-phone" aria-hidden="true"></i><?php echo $tel; ?></a>
						<?php if ( $tel_time ) { ?>
							<div class="l-offer__subtext"><?php echo $tel_time; ?></div>
						<?php } ?>
					</div>
					<div class="large-6 small-12">
						<a class="l-offer__button" href="<?php echo $contact_url; ?>"><?php echo $contact_text; ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
