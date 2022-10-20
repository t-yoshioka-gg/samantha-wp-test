<?php
/**
 * [レイアウト]
 * グローバルナビゲーション
 *
 * @category components
 * @package growp
 * @since 1.0.0
 */
?>
<nav class="l-global-nav">
	<div class="l-container">
		<?php
		// メニューをレンダリング
		GNav::render_menus( "global_nav" );
		?>
	</div>
</nav>
