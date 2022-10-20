<?php
$buttons = get_sub_field( 'buttons' );
if ( ! $buttons ) {
	return;
}
?>
<div class="l-container">
	<div class="c-capsul-nav">
		<?php
		foreach ( $buttons as $button ) {
			if ( ! $button["link"] || ! $button["text"] ) {
				continue;
			}
			$class  = "c-capsul-nav__block c-button ";
			$target = "_self";
			switch ( $button["target"] ) {
				case "outside":
					$target = "_blank";
					break;
				case "inpage":
					$class .= " is-pagelink";
					$class .= " js-anchor";
					break;
				default:
					break;
			}
			?>
			<a class="<?php echo $class; ?>" target="<?php echo $target; ?>" href="<?php echo $button["link"]; ?>"><?php echo $button["text"]; ?></a>
			<?php
		}
		?>
	</div>
</div>
