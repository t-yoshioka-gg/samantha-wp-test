<?php
$content = get_sub_field( 'content' );
if ( ! $content ) {
	return;
}
?>
<div class="l-container">
	<div class="l-post-content">
		<?php
		echo $content;
		?>
	</div>
</div>
