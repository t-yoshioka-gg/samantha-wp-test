<?php
/**
 * [レイアウト]
 * ヘッダー
 *
 * @category components
 * @package growp
 * @since 1.0.0
 */

// 管理画面＞サイトオプションより設定
// 第2引数にテンプレートを渡すと自動でサイトオプションを更新する
gm_site::update_template( "o_site_header", function (){
	?>
	<header class="l-header js-fixedheader">
		<div class="l-header__content">
			[growp_component name="util/logo-start-tag"]<a href="<?php Gurl::the_url() ?>/"><img src="<?php GUrl::the_asset() ?>/assets/images/logo.png" alt="サマンサ・ホームステージング"/></a>
			[growp_component name="util/logo-end-tag"]
			<nav class="l-header__nav"><a class="l-header__news" href="<?php Gurl::the_url() ?>/news/">お知らせ一覧<span class="material-symbols-outlined icon-arrow">chevron_right</span></a>
				<ul>
					<li><a href="<?php Gurl::the_url() ?>/about-us/">私たちについて</a>
					</li>
					<li><a href="<?php Gurl::the_url() ?>/homestaging/">ホームステージングとは</a>
					</li>
					<li><a class="l-header__parent" href="<?php Gurl::the_url() ?>/service/">サービス</a>
						<div class="l-header__submenu">
							<div class="l-header__submenu__outer">
								<div class="l-header__submenu__title">
									<div class="c-heading is-xlg"><small>SERVICE</small><b>サービス</b></div>
								</div>
								<div class="l-header__submenu__content">
									<div class="l-header__submenu__table">
										<ul>
											<li><a class="l-header__submenu__block" href="<?php Gurl::the_url() ?>/service/vacant/">空室ホームステージング</a>
											</li>
											<li><a class="l-header__submenu__block" href="<?php Gurl::the_url() ?>/service/occupied/">居住中ホームステージング</a>
											</li>
											<li><a class="l-header__submenu__block" href="<?php Gurl::the_url() ?>/service/still-photography/">スチール写真撮影</a>
											</li>
										</ul>
										<ul>
											<li><a class="l-header__submenu__block" href="<?php Gurl::the_url() ?>/service/still-photography/#3d-walk">3Dウォークスルー動画撮影</a>
											</li>
											<li><a class="l-header__submenu__block" href="<?php Gurl::the_url() ?>/service/vacant/#vr-home-stasing">バーチャルホームステージング<br/>roOomy</a>
											</li>
											<li><a class="l-header__submenu__block" href="<?php Gurl::the_url() ?>/service/home-organization/">片付け代行</a>
											</li>
										</ul>
										<ul>
											<li><a class="l-header__submenu__block" href="<?php Gurl::the_url() ?>/service/packing-unpacking/">荷造り・荷解き代行</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</li>
					<li><a class="l-header__parent" href="<?php Gurl::the_url() ?>/useful/">お役立ち情報</a>
						<div class="l-header__submenu">
							<div class="l-header__submenu__outer">
								<div class="l-header__submenu__title">
									<div class="c-heading is-xlg"><small>COLUMN</small><b>お役立ち情報</b></div>
								</div>
								<div class="l-header__submenu__content">
									<div class="l-header__submenu__flex"><a class="l-header__submenu__block" href="<?php Gurl::the_url() ?>/useful/category/">お役立ち情報</a><a class="l-header__submenu__block" href="<?php Gurl::the_url() ?>/useful/category/">用語集</a>
									</div>
								</div>
							</div>
						</div>
					</li>
					<li><a href="<?php Gurl::the_url() ?>/company/">会社情報</a>
					</li>
					<li><a href="<?php Gurl::the_url() ?>/recruit/">採用情報</a>
					</li>
				</ul>
			</nav><a class="l-header__contact" href="<?php Gurl::the_url() ?>/contact/"><span class="icon-contact"></span>お問い合わせ</a>
		</div>
	</header>
	<?php
});
