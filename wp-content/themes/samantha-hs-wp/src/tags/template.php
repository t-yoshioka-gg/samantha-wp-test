<?php

/**
 * Class GTemplate
 * テンプレートの操作関連
 */
class GTemplate {
	/**
	 * テンプレートをインクルード
	 *
	 * @param $file
	 * @param array $data
	 *
	 * @return bool
	 */
	public static function get_template( $file, $data = array() ) {
		$file      = apply_filters( 'growp/template/filepath', $file );
		$file_path = GROWP_TEMPLATE_PATH . "/views/" . $file . ".php";
		if ( $file && file_exists( $file_path ) ) {
			include $file_path;

			return true;
		}
		if ( $file ) {
			self::error();
		}
	}

	/**
	 * レイアウト用コンポーネントをインクルード
	 *
	 * @param $file
	 * @param array $data
	 */
	public static function get_layout( $file, $data = array() ) {
		self::get_template( 'layout/' . $file, $data );
	}

	/**
	 * コンポーネントをインクルード
	 *
	 * @param $file
	 * @param array $data
	 */
	public static function get_component( $file, $data = array() ) {
		self::get_template( 'object/components/' . $file, $data );
	}

	/**
	 * ブロックをインクルード
	 *
	 * @param $file
	 * @param array $data
	 */
	public static function get_block( $file, $data = array() ) {
		self::get_template( 'object/components/blocks/' . $file, $data );
	}

	/**
	 * プロジェクト依存コンポーネントをインクルード
	 *
	 * @param $file
	 * @param array $data
	 */
	public static function get_project( $file, $data = array() ) {
		self::get_template( 'object/project/' . $file, $data );
	}

	/**
	 * WordPressにログインしている時に取得する
	 */
	private static function error() {
		if ( is_user_logged_in() ) {
			echo "テンプレートがありません";
		}
	}

	/**
	 * メインテンプレートの内容を呼び出す
	 * @return string
	 */
	public static function get_raw_content() {
		ob_start();
		load_template( GTag::get_template_path(), false );
		$templatedata = ob_get_contents();
		ob_end_clean();
		do_action("growp_get_content", $templatedata);
		return $templatedata;
	}

	/**
	 * プロジェクトテンプレートを用いて記事を表示する
	 */
	public static function subloop( $posts, $project_template_name ) {
		global $post;
		if(!$posts){
			return ;
		}
		foreach ( $posts as $post ) {
			setup_postdata( $post );
			GTemplate::get_project( $project_template_name );
		}
		wp_reset_postdata();
	}

}
