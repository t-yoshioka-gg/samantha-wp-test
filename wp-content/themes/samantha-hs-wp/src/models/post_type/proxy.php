<?php
/**
 * 渡された記事IDより投稿タイプを取得し、
 * 該当するモデルのインスタンスを取得する
 */
class gm_proxy {

	/**
	 * @param $post_id
	 *
	 * @return gm_base_post mixed|null
	 */
	public static function get_instance( $post_id ) {
		if ( is_object( $post_id ) ) {
			$post = $post_id;
		} else {
			$post = get_post( $post_id );
		}

		$post_type = "";
		if ( isset( $post->post_type ) && $post->post_type ) {
			$post_type = $post->post_type;
		}
		if ( ! $post_type ) {
			return null;
		}
		$class_name = "gm_" . $post_type;
		if ( class_exists( $class_name ) ) {
			return new $class_name( $post_id );
		}

		return null;
	}

	public static function from_global() {
		$post_id = get_the_ID();
		return static::get_instance( $post_id );
	}
}