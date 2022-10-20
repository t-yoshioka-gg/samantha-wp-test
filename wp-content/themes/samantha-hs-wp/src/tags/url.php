<?php

/**
 * Class GUrl
 * URL関連の処理
 */
class GUrl {

	/**
	 * URLを取得
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	public static function url( $path = "" ) {
		return esc_url( home_url( $path ) );
	}

	/**
	 * URLを出力
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	public static function the_url( $path = "" ) {
		echo esc_url( home_url( $path ) );
	}

	/**
	 * 子テーマディレクトリのURLを取得する
	 *
	 * @param $path
	 *
	 * @return string
	 */
	public static function asset( $path = "" ) {
		return esc_url( get_stylesheet_directory_uri() . $path );
	}

	/**
	 * 子テーマディレクトリのURLを出力
	 *
	 * @param $path
	 *
	 * @return string
	 */
	public static function the_asset( $path = "" ) {
		echo static::asset( $path );
	}

}
