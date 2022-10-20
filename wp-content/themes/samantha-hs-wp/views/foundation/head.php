<?php
/**
 * サイト共通 head 内 タグ
 *
 * @category components
 * @package growp
 * @since 1.0.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
    <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?php GUrl::the_asset() ?>/assets/images/favicon.ico" rel="icon" />
    <link href="<?php GUrl::the_asset() ?>/assets/images/favicon.ico" rel="shortcut icon" />
    <link href="<?php GUrl::the_asset() ?>/assets/images/web-clipicon.png" rel="apple-touch-icon" />
	<link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round|Material+Icons+Sharp|Material+Icons+Two+Tone|Material+Symbols+Outlined" rel="stylesheet" />
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>
    <?php
    // 管理画面＞サイトオプションより設定
    echo do_shortcode( get_field( "o_head", "option" ) );
    ?>
	<?php wp_head(); ?>
    </head>

<body <?php body_class(); ?>>
<?php
// 管理画面＞サイトオプションより設定
echo do_shortcode( get_field( "o_body_prepend", "option" ) );
?>
<?php
// 管理画面＞サイトオプションより設定
// 第2引数にテンプレートを渡すと自動でサイトオプションを更新する
gm_site::update_template( "o_site_slidebar", function () {
	?>
	<button class="c-slidebar-button js-slidebar-button" type="button"><span class="c-slidebar-button__inner"><span class="c-slidebar-button__line"><span></span><span></span><span></span></span></span></button>
	<div class="c-slidebar-menu js-slidebar-menu is-top-to-bottom">
		<ul class="c-slidebar-menu__list">
			<li><a href="<?php Gurl::the_url() ?>/about-us/">私たちについて</a>
			</li>
			<li><a href="<?php Gurl::the_url() ?>/homestaging/">ホームステージングとは</a>
			</li>
			<li class="c-slidebar-menu__parent js-accordion"><span data-accordion-title="menu-title">サービス</span>
				<ul class="c-slidebar-menu__children" data-accordion-content="menu-text">
					<li><a href="<?php Gurl::the_url() ?>/service/vacant/">空室ホームステージング</a>
					</li>
					<li><a href="<?php Gurl::the_url() ?>/service/occupied/">居住中ホームステージング</a>
					</li>
					<li><a href="<?php Gurl::the_url() ?>/service/still-photography/">スチール写真撮影</a>
					</li>
					<li><a href="<?php Gurl::the_url() ?>/service/still-photography/#walkthrough">3Dウォークスルー動画撮影</a>
					</li>
					<li><a href="<?php Gurl::the_url() ?>/service/vacant/#vr-home-stasing">バーチャルホームステージング<bt/>roOomy</a>
					</li>
					<li><a href="<?php Gurl::the_url() ?>/service/home-organization/">片付け代行</a>
					</li>
					<li><a href="<?php Gurl::the_url() ?>/service/packing-unpacking/">荷造り・荷解き代行</a>
					</li>
				</ul>
			</li>
			<li class="c-slidebar-menu__parent js-accordion"><span data-accordion-title="menu-title">お役立ち情報</span>
				<ul class="c-slidebar-menu__children" data-accordion-content="menu-text">
					<li><a href="<?php Gurl::the_url() ?>/useful/">お役立ち情報</a>
					</li>
					<li><a href="<?php Gurl::the_url() ?>/useful/category/">用語集</a>
					</li>
				</ul>
			</li>
			<li><a href="<?php Gurl::the_url() ?>/company/">会社情報</a>
			</li>
			<li><a href="<?php Gurl::the_url() ?>/recruit/">採用情報</a>
			</li>
		</ul>
		<div class="c-slidebar-menu__bottom"><a class="c-slidebar-menu__link" href="<?php Gurl::the_url() ?>/news/">お知らせ一覧</a><a class="c-slidebar-menu__button c-button__contact" href="<?php Gurl::the_url() ?>/contact/"><span class="icon-contact"></span>お問い合わせ</a>
		</div>
	</div>
	<?php
} );
