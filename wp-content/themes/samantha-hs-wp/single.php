<?php
/**
 * 投稿テンプレート
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * @see http://codex.wordpress.org/Template_Hierarchy
 * =====================================================
 */
the_post();

$post_object = gm_post::from_global();
?>

<section class="l-section is-lg">
	<div class="l-container">
		<div class="row">
			<div class="large-8 is-push-lg-2 small-12">
				<div class="c-news-header">
					<h1 class="c-news-header__title">
						<?php echo $post_object->get_the_title(); ?>
					</h1>
					<div class="c-news-header__sup">
						<?php
						$categories = $post_object->get_terms( "category" );
						foreach ( $categories as $category ){
							?>
							<div class="c-news-header__label">
								<?php echo $category->get_name(); ?>
							</div>
							<?php
						}
						?>
						<div class="c-news-header__date">
							<?php echo $post_object->get_the_date("Y.m.d") ?>
						</div>
					</div>
				</div>
		
				<div class="l-post-content u-mbs is-sm">
					<?php
					echo $post_object->get_content();
					?>
				</div>
				<div class="u-mbs">
					<div class="c-button-social">
						<?php
						$post_object->the_social_icons();
						?>
					</div>
				</div>
				<hr class="c-hr">
				<?php
				gm_post::the_post_nav();
				?>
				<?php
				$related_posts = $post_object->get_related_posts();
				if ( $related_posts ) {
					?>
					<div class="u-mbs is-top is-lg">
						<div class="u-mbs is-bottom is-sm">
							<h2 class="c-heading is-lg is-border"><span>関連コンテンツ</span></h2>
						</div>
						<div class="c-news  is-onecolumn">
							<div class="c-news__content">
								<?php
								global $post;
								foreach ( $related_posts as $post ) {
									setup_postdata( $post );
									GTemplate::get_project( "post-item" );
								}
								wp_reset_postdata();
								?>
							</div>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</section>
