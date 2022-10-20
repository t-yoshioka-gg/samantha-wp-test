<?php
/**
 * custom theme comment template.
 * =====================================================
 * @package  growp
 * @license  GPLv2 or later
 * @since 1.0.0
 * =====================================================
 */
/**
 * Change comment template.
 *
 * @param string $comment_template
 *
 * @return string comment template path.
 */

add_filter( 'comments_template', 'growp_plugin_comment_template', 10, 1 );

function growp_plugin_comment_template( $comment_template ) {

	return get_template_directory() . '/views/object/components/comments.php';

}

/**
 * Customize the text prior to the comment form fields
 *
 * @param  array $defaults
 *
 * @return $defaults
 *
 */

add_filter( 'comment_form_defaults', 'growp_comment_form_defaults' );

function growp_comment_form_defaults( $defaults ) {

	$defaults['title_reply']          = __( 'Post a new comment', 'growp' );
	$defaults['comment_notes_before'] = '<p class="comment-notes">' . __( 'Your email address will not be published. Required fields are marked',
			'growp' ) . '</p>';

	return $defaults;

}

/**
 * Modify the comment form input fields
 *
 * @param  $args http://codex.wordpress.org/Function_Reference/comment_form
 * $args['author']
 * $args['email']
 * $args['url']
 *
 * @return $args
 */

add_filter( 'comment_form_default_fields', 'growp_comment_form_args' );

function growp_comment_form_args( $args ) {
	$comment_author       = isset( $commenter['comment_author'] ) ? $commenter['comment_author'] : '';
	$comment_author_email = isset( $commenter['comment_author_email'] ) ? $commenter['comment_author_email'] : '';
	$comment_author_url   = isset( $commenter['comment_author_url'] ) ? $commenter['comment_author_url'] : '';
	$args['author']       = '<p class="comment-form-author"><input id="author" name="author" type="text" value="' . esc_attr( $comment_author ) . '" size="40" tabindex="1" aria-required="true" title="' . __( 'Your Name (required)',
			'growp' ) . '" placeholder="' . __( 'Your Name (required)',
			'growp' ) . '" required /><!-- .comment-form-author .form-section --></p>';
	$args['email']        = '<p class="comment-form-email"><input id="email" name="email" type="email" value="' . esc_attr( $comment_author_email ) . '" size="40" tabindex="2" aria-required="true" title="' . __( 'Email Address (required)',
			'growp' ) . '" placeholder="' . __( 'Email Address (required)',
			'growp' ) . '" required /><!-- .comment-form-email .form-section --></p>';
	$args['url']          = '<p class="comment-form-url"><input id="url" name="url" type="url" value="' . esc_attr( $comment_author_url ) . '" size="40" tabindex="3" aria-required="false" title="' . __( 'Website (url)',
			'growp' ) . '" placeholder="' . __( 'Website (url)',
			'growp' ) . '" required /><!-- .comment-form-url .form-section --></p>';

	return $args;

}

/**
 * Customize the comment form comment field
 *
 * @param  string $field
 *
 * @return string
 */

add_filter( 'comment_form_field_comment', 'growp_comment_form_field_comment' );

function growp_comment_form_field_comment( $field ) {

	$field = '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" tabindex="4" title="' . __( 'Comment',
			'growp' ) . '" placeholder="' . __( 'Comment',
			'growp' ) . '" aria-required="true"></textarea><!-- #form-section-comment .form-section --></p>';

	return $field;

}
