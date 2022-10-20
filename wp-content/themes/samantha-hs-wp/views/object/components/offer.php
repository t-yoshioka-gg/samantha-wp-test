<?php
/**
 * オファー ( CTA...etc )
 *
 * @category components
 * @package growp
 * @since 1.0.0
 */

// 管理画面＞サイトオプションより設定
// 第2引数にテンプレートを渡すと自動でサイトオプションを更新する
gm_site::update_template( "o_site_offer", function (){
	?>
	<div class="l-offer">
		<div class="l-container">
			<div class="l-offer__inner">
				<h2 class="l-offer__title is-bottom"><b>CONTACT</b><small>お気軽にお問い合わせください</small></h2>
				<div class="l-offer__text">ホームステージング・物件撮影・お片付け・お引越し時のお荷物の整理・梱包・収納等、<br class="u-hidden-sm">サービスに関するお問い合わせは、お電話または専用フォームより受け付けております。</div>
				<div class="l-offer__items">
					<div class="l-offer__box">
						<div class="l-offer__box-tel"><a href="tel:050-1741-6970"><span class="material-icons icon-phone">call</span>050-1741-6970</a><small>【受付時間】9:00～18:00（年末年始を除く ）</small></div>
					</div>
					<div class="l-offer__box"><a class="c-button__contact" href="<?php Gurl::the_url() ?>/contact/"><span class="icon-contact"></span>お問い合わせフォームはこちら</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
});
