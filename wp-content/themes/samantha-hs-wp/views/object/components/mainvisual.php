<?php
/**
 * メインビジュアル
 *
 * @category components
 * @package growp
 * @since 1.0.0
 */
if ( ! is_front_page() ){
	return false;
}
?>

<div class="c-main-visual-block">
	<div class="c-main-visual__scroll js-anchor" data-anchor-target="#main"><small>Scroll Down</small><span></span></div>
	<div class="c-main-visual">
		<div class="c-main-visual__slider">
			<div class="c-main-visual__main">
				<div class="c-main-visual__item">
					<div class="c-main-visual__item-image is-pc" style="background-image: url(<?php GUrl::the_asset() ?>/assets/images/img-main-visual-01.jpg)">
					</div>
					<div class="c-main-visual__item-image is-sp" style="background-image: url(<?php GUrl::the_asset() ?>/assets/images/img-main-visual-01-sp.jpg)">
					</div>
					<div class="c-main-visual__ba  is-before">Before
					</div>
					<div class="c-main-visual__ba  is-after">After
					</div>
					<div class="c-main-visual__item-content">
						<div class="c-heading is-xlg"><b>「住みたい」を引き出す、<br class="u-hidden-sm"/>不動産演出</b><small>HOME STAGING SERVICE</small><small>CASEⅠ</small></div>
					</div>
				</div>
				<div class="c-main-visual__item">
					<div class="c-main-visual__item-image is-pc" style="background-image: url(<?php GUrl::the_asset() ?>/assets/images/img-main-visual-02.jpg)">
					</div>
					<div class="c-main-visual__item-image is-sp" style="background-image: url(<?php GUrl::the_asset() ?>/assets/images/img-main-visual-02-sp.jpg)">
					</div>
					<div class="c-main-visual__ba  is-before">Before
					</div>
					<div class="c-main-visual__ba  is-after">After
					</div>
					<div class="c-main-visual__item-content">
						<div class="c-heading is-xlg"><b>快適な暮らしづくり</b><small>Organize and Manage Possessions</small><small>CASEⅡ</small></div>
					</div>
				</div>
				<div class="c-main-visual__item">
					<div class="c-main-visual__item-image is-pc" style="background-image: url(<?php GUrl::the_asset() ?>/assets/images/img-main-visual-03.jpg)">
					</div>
					<div class="c-main-visual__item-image is-sp" style="background-image: url(<?php GUrl::the_asset() ?>/assets/images/img-main-visual-03-sp.jpg)">
					</div>
					<div class="c-main-visual__ba  is-before">Before
					</div>
					<div class="c-main-visual__ba  is-after">After
					</div>
					<div class="c-main-visual__item-content">
						<div class="c-heading"><b>新生活を迎える<br class="u-hidden-sm"/>準備をサポート</b><small>Packing / Unpacking Support</small><small>CASEⅢ</small></div>
					</div>
				</div>
				<!--+e.item-->
				<!--  +bgimg("img-main-visual-04.jpg").c-main-visual__item-image.is-pc-->
				<!--  +bgimg("img-main-visual-04-sp.jpg").c-main-visual__item-image.is-sp-->
				<!--  +e.item-content-->
				<!--    .c-heading-->
				<!--      b 「新しい生活様式」に<br class="u-hidden-sm"/>合わせた最新技術-->
				<!--      small Utilizing VR in Home Staging-->
			</div>
		</div>
		<div class="c-main-visual__slider-progress">
			<div class="c-main-visual__slider-progress-bar">
			</div>
		</div>
	</div>
	<div class="c-top-lead">
		<div class="c-top-lead__inner">
			<div class="c-top-lead__image">
				<div class="c-top-lead__image-pic01" style="background-image: url(<?php GUrl::the_asset() ?>/assets/images/img-top-lead-01.jpg)">
				</div>
				<div class="c-top-lead__image-pic02" style="background-image: url(<?php GUrl::the_asset() ?>/assets/images/img-top-lead-02.jpg)">
				</div>
			</div>
			<div class="c-top-lead__content">
				<h2 class="c-heading is-xlg"><small>ABOUT SAMANTHA HOME STAGING</small><b>不動産をスピーディーに<br/>売却するための<br class="u-hidden-lg" />「空間演出」</b></h2>
				<p class="c-top-lead__text">
					家具・小物の設置や片付けなど、物件本来の魅力が伝わる演出を施すことで、<br class="u-hidden-sm">
					検討者の購買意欲を掻き立てる「売却支援サービス」です。<br/>
					適切な演出＝ホームステージングを行うことにより明るく清潔な印象を与え、そこでの新生活をイメージさせることができ、早期売却につながりやすくなります。
				</p>
				<ul class="c-top-lead__links">
					<li><a class="c-button" href="<?php Gurl::the_url() ?>/homestaging/">ホームステージングとは</a>
					</li>
					<li><a class="c-button" href="<?php Gurl::the_url() ?>/about-us/#homestager">ホームステージャーについて</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
