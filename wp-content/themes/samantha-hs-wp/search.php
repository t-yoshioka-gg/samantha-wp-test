<?php
/**
 * 検索結果用テンプレート
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * @see http://codex.wordpress.org/Template_Hierarchy
 * =====================================================
 */

?>
<section class="l-section is-xxlg">
	<div class="l-container">
		<div class="row">
			<div class="large-10 is-push-lg-1 small-12">
				<div class="u-mbs is-bottom is-lg">
					<div class="c-archive">
						<header class="page-header">
							<h1 class="page-title">
								<i class="fa fa-search"></i>
								<?php
								$search_text = __( 'Search Results for&#x3A; %s', 'growp' );
								printf( $search_text, '<span>「' . get_search_query() . '」</span>' ); ?></h1>
						</header><!-- .page-header -->
						<div class="c-news  is-onecolumn">
							<div class="c-news__content">
								<?php
								if ( have_posts() ) { 
									while ( have_posts() ) {
										the_post();
										GTemplate::get_project( "search-item" );
									}

								} else {
									?>
									<div class="u-mbs is-xxlg u-text-center " style="width: 100% !important;">
										該当がありませんでした
									</div>
								<?php
								}
								?>
							</div>

							<?php
							echo GNav::get_paging_nav();
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

