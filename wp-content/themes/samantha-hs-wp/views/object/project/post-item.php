<?php
/**
 * 投稿 : ループ中テンプレート
 */
$post_object = gm_post::from_global();
$post_item_tag = "a";
$post_item_href = 'href="'.$post_object->get_permalink().'"';
$post_item_target = 'target="'.$post_object->get_link_target().'"';
if ( ! $post_object->is_info_link_enable() ) {
	$post_item_tag = "div";
	$post_item_href ="";
	$post_item_target ="";
}
?>
<<?php echo $post_item_tag ?> class="c-card-thumb js-slider-item" <?php echo $post_item_href ?> <?php echo $post_item_target ?>>
	<div class="c-card-thumb__img" style="background-image: url(<?php echo $post_object->get_thumbnail_url() ?>)">
	</div>
	<div class="c-card-thumb__content"><span class="c-card-thumb__category"><?php echo $post_object->get_first_term_name("category") ?></span>
		<div class="c-card-thumb__title"><?php echo $post_object->get_the_title() ?> <?php echo $post_object->get_link_icon(); ?></div>
		<div class="c-card-thumb__date"><?php echo $post_object->get_the_date("Y.m.d") ?></div>
	</div>
</<?php echo $post_item_tag ?>>

<?php
/**
 <a class="c-card-thumb js-slider-item" href="<?php Gurl::the_url() ?>/news/page/">
	<div class="c-card-thumb__img" style="background-image: url(<?php GUrl::the_asset() ?>/assets/images/img-default.jpg)">
	</div>
	<div class="c-card-thumb__content"><span class="c-card-thumb__category">カテゴリが入ります</span>
		<div class="c-card-thumb__title">2行まで投稿タイトルが入ります。投稿タイトルが入ります。投稿タイトルがが入ります…</div>
		<div class="c-card-thumb__date">2022.05.31</div>
	</div>
</a>
*/

