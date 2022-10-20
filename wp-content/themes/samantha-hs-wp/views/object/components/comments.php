<?php
/**
 * コメント
 *
 * @category components
 * @package growp
 * @since 1.0.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div class="l-container">
	<div id="comments" class="comments">

		<?php // You can start editing here -- including this comment! ?>

		<?php if ( have_comments() ) : ?>
			<h4 class="comments-title">
				<?php
				printf( _nx( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;',
					get_comments_number(), 'comments title', 'growp' ),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
				?>
			</h4>

			<?php
			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through
				?>
				<nav id="comment-nav-above" class="comments-navigation" role="navigation">
					<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'growp' ); ?></h1>
					<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments',
							'growp' ) ); ?></div>
					<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'growp' ) ); ?></div>
				</nav><!-- #comment-nav-above -->        <?php
			endif; // check for comment navigation

			wp_list_comments( array(
				'walker'       => new GROWP_Walker_Comment,
				'style'        => 'ul',
				'callback'     => null,
				'end-callback' => null,
				'short_ping'   => true,
				'type'         => 'all',
				'avatar_size'  => 90,
				'page'         => null,
			) );

			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through
				?>
				<nav id="comment-nav-below" class="comment-navigation navigation" role="navigation">
					<h1 class="screen-reader-text navigation-title"><?php _e( 'Comment navigation', 'growp' ); ?></h1>
					<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments',
							'growp' ) ); ?></div>
					<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'growp' ) ); ?></div>
				</nav><!-- #comment-nav-below -->        <?php endif; // check for comment navigation ?>

		<?php endif; // have_comments() ?>

		<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
			?>
			<p class="no-comments"><?php _e( 'Comments are closed.', 'growp' ); ?></p>    <?php endif; ?>

		<?php
		comment_form( array( 'class_submit' => 'c-button is-submit' ) ); ?>

	</div><!-- #comments -->
</div>
