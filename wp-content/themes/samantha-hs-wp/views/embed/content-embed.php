<?php

/**
 * Contains the post embed content template part
 *
 * When a post is embedded in an iframe, this file is used to create the content template part
 * output if the active theme does not include an embed-content.php template.
 *
 * @package WordPress
 * @subpackage Theme_Compat
 * @since 4.5.0
 */
$thumbnail_id = 0;
if ( has_post_thumbnail() ) {
	$thumbnail_id = get_post_thumbnail_id();
}

if ( 'attachment' === get_post_type() && wp_attachment_is_image() ) {
	$thumbnail_id = get_the_ID();
}
$content = get_the_excerpt();
$class      = "";
$show_image = true;
if ( empty( trim( $content ) ) ) {
	$class      = "is-title-only";
	$show_image = false;
}
?>
<div <?php post_class( 'c-embed' . " " . $class ); ?>>
	<?php
	?>

	<p class="c-embed__heading">
		<a href="<?php the_permalink(); ?>" target="_top">
			<?php the_title(); ?>
		</a>
	</p>
	<div class="c-embed__inner">
		<?php if ( has_post_thumbnail() && $show_image ) { ?>
			<div class="c-embed__image">
				<a href="<?php the_permalink(); ?>" target="_top">
					<div class="c-embed__image-bg" style="background-image: url(<?php echo GTag::get_attachment_url( $thumbnail_id, "large" ); ?>);"></div>
				</a>
			</div>
		<?php } ?>
		<div class="c-embed__content">
			<a href="<?php the_permalink(); ?>" target="_top">
				<?php echo $content; ?>
			</a>
		</div>
	</div>
</div>
