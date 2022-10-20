<?php
/**
 * アーカイブテンプレート
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * @see http://codex.wordpress.org/Template_Hierarchy
 * =====================================================
 */

$categories = gm_category::get_all_items();
?>
<section class="l-section is-lower-case">
	<div class="l-container">
		<div class="row">
			<div class="large-10 is-push-lg-1 small-12">
				<div class="u-mbs is-bottom is-lg">
					<div class="c-box-archive">
						<div class="c-box-archive__block">
							<div class="c-box-archive__title">カテゴリ</div>
							<ul>
								<li>
									<a class="<?php echo is_home() ? "is-active" : ""; ?>" href="<?php echo get_post_type_archive_link( 'post' ); ?>"><span>すべて</span></a>
								</li>
								<?php 
								foreach( $categories as $category ){
									?>
									<li>
										<a class="<?php echo $category->is_tax() ? "is-active" : "" ?>" href="<?php echo $category->get_archive_link(); ?>"><?php echo $category->get_name(); ?></a>
									<?php
								}
								?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="c-news  is-onecolumn">
			<div class="row">
				<div class="large-10 is-push-lg-1 small-12">
					<div class="c-news__content">
						<?php
						if ( have_posts() ) :
							/* ループをスタート */
							while ( have_posts() ) : the_post();
								GTemplate::get_project( "post-item" );
							endwhile;
						else :
							get_template_part( 'content', 'none' );
						endif;
						?>
					</div>
					<?php
					echo GNav::get_paging_nav();
					?>
					<div class="u-mbs is-top is-lg">
						<div class="c-box-archive">
							<div class="c-box-archive__block">
								<div class="c-box-archive__title">年月アーカイブ</div>
								<ul>
									<?php
									$archive_str = wp_get_archives( [
										'type' => "monthly",
										'echo' => false
									] );

									echo $archive_str;
									?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
