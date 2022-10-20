<?php
/**
 * [レイアウト]
 * フッター
 *
 * @category components
 * @package growp
 * @since 1.0.0
 */
?>

<?php GTemplate::get_component( "offer" ); ?>

<?php
// 管理画面＞サイトオプションより設定
// 第2引数にテンプレートを渡すと自動でサイトオプションを更新する
gm_site::update_template( "o_site_footer", function () {
	?>
	<footer class="l-footer"><a class="js-anchor c-pagetop" href="html" data-anchor-target="html"></a>
		<div class="l-container">
			<div class="l-footer__main">
				<div class="l-footer__content"><a class="l-footer__logo" href="<?php Gurl::the_url() ?>/"><img src="<?php GUrl::the_asset() ?>/assets/images/logo.png" alt="サマンサホームステージング"/></a>
					<address class="l-footer__address"><b>東京本社</b>〒135-0047　東京都江東区富岡1丁目12-8　アサヒビル 2階<br/>
																  TEL：050-1741-6970（代）　FAX：03-5875-8692
					</address>
					<ul class="l-footer__bases">
						<li><a href="<?php Gurl::the_url() ?>/company/#outline">事業所一覧</a>
						</li>
						<li><a href="<?php Gurl::the_url() ?>/showroom/">日本橋ショールーム</a>
						</li>
					</ul>
					<ul class="l-footer__sns">
						<li><a class="is-instagram" href="https://www.instagram.com/samantha_homestaging/" target="_blank"><img src="<?php GUrl::the_asset() ?>/assets/images/icon-instagram.png" alt="instagram"/></a>
						</li>
						<li><a class="is-twitter" href="https://twitter.com/samanthahs01" target="_blank"><img src="<?php GUrl::the_asset() ?>/assets/images/icon-twitter.png" alt="twitter"/></a>
						</li>
					</ul>
				</div>
				<div class="l-footer__sitemap">
					<div class="l-footer__menu is-left">
						<div class="l-footer__block">
							<ul class="l-footer__menulist">
								<li><a href="<?php Gurl::the_url() ?>/">ホーム</a>
								</li>
								<li><a href="<?php Gurl::the_url() ?>/about-us/">私たちについて</a>
								</li>
								<li><a href="<?php Gurl::the_url() ?>/homestaging/">ホームステージングとは</a>
								</li>
							</ul>
						</div>
						<div class="l-footer__block"><a class="l-footer__menutitle" href="<?php Gurl::the_url() ?>/service/">サービス</a>
							<ul class="l-footer__menulist is-sub">
								<li><a href="<?php Gurl::the_url() ?>/service/vacant/">空室ホームステージング</a>
								</li>
								<li><a href="<?php Gurl::the_url() ?>/service/occupied/">居住中ホームステージング</a>
								</li>
								<li><a href="<?php Gurl::the_url() ?>/service/still-photography/">スチール写真撮影</a>
								</li>
								<li><a href="<?php Gurl::the_url() ?>/service/still-photography/#3d-walk">3Dウォークスルー動画撮影</a>
								</li>
								<li><a href="<?php Gurl::the_url() ?>/service/vacant/#vr-home-stasing">バーチャルホームステージング <br class="u-hidden-sm"/>roOomy</a>
								</li>
								<li><a href="<?php Gurl::the_url() ?>/service/home-organization/">片付け代行</a>
								</li>
								<li><a href="<?php Gurl::the_url() ?>/service/packing-unpacking/">荷造り・荷解き代行</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="l-footer__menu is-right">
						<div class="l-footer__block"><a class="l-footer__menutitle" href="<?php Gurl::the_url() ?>/useful/">お役立ち情報</a>
							<ul class="l-footer__menulist is-sub">
								<li><a href="<?php Gurl::the_url() ?>/useful/category/">お役立ち情報</a>
								</li>
								<li><a href="<?php Gurl::the_url() ?>/useful/category/">用語集</a>
								</li>
							</ul><a class="l-footer__menutitle" href="<?php Gurl::the_url() ?>/news/">お知らせ</a>
							<ul class="l-footer__menulist is-sub">
								<li><a href="<?php Gurl::the_url() ?>/news/category/">ニュースリリース</a>
								</li>
								<li><a href="<?php Gurl::the_url() ?>/news/category/">メディア出演</a>
								</li>
								<li><a href="<?php Gurl::the_url() ?>/news/category/">ブログ</a>
								</li>
							</ul>
						</div>
						<div class="l-footer__block"><a class="l-footer__menutitle" href="<?php Gurl::the_url() ?>/company/"><span>会社情報</span></a>
							<ul class="l-footer__menulist is-sub">
								<li><a href="<?php Gurl::the_url() ?>/showroom/">ショールーム</a>
								</li>
							</ul><a class="l-footer__menutitle" href="<?php Gurl::the_url() ?>/recruit/">採用情報</a><a class="l-footer__menutitle" href="<?php Gurl::the_url() ?>/contact/">お問い合わせ</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="l-footer__bottom">
			<div class="l-container">
				<div class="l-footer__bottom-content"><small class="l-footer__copyright">Copyright © サマンサ・ホームステージング</small>
					<ul class="l-footer__links">
						<li><a href="<?php Gurl::the_url() ?>/legal-notation/">特定商取引法に基づく表記</a>
						</li>
						<li><a href="<?php Gurl::the_url() ?>/privacy-policy/">個人情報の取り扱いについて</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</footer>
	<?php
} );
