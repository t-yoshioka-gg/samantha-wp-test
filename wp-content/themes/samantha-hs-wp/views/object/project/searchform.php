<?php
/**
 * サイト共通 検索フォーム
 *
 * @category components
 * @package growp
 * @since 1.0.0
 */
?>
<form role="form" action="<?php echo site_url( '/' ); ?>" id="searchform" class="block" method="get">
	<label for="s" class="screen-reader-text"><?php _e( 'Search', 'growp' ); ?></label>
	<div class="row ">
		<div class="small-7">
			<input type="text" class="form-control" id="s" name="s" placeholder="<?php _e( 'Search',
				'growp' ); ?>" value="" />
		</div>
		<div class="small-5">
			<button type="submit" class="button postfix"><?php _e( 'Submit', 'growp' ); ?> </button>
		</div>
	</div>
	<!-- .input-group -->
</form>
