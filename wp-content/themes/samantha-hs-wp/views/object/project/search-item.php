<?php
/**
 * 記事一覧時の1記事分のテンプレート
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */
$post_object = gm_proxy::get_instance( get_the_ID() );
?>
<a class="c-news__block" href="<?php echo $post_object->get_permalink() ?>">
	<div class="c-news__inner">
		<div class="c-news__text">
			<?php echo $post_object->get_the_title() ?>
		</div>
	</div>
</a>
