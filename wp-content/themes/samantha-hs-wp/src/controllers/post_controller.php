<?php
/**
 * # 絞り込み検索など記事ごとの一覧の挙動を変える時のコントローラー
 * カスタム投稿タイプなどで絞り込み検索がある場合は以下の処理を行う。
 *
 * 1. このファイルを{post_type}_controllerというファイル名で複製
 * 2. gm_{post_type}_controller というクラス名にリネーム
 * 3. public $post_type = "{post_type}" に変更
 * 4. public $default_search_query_vars に検索に利用するパラメータのデフォルト値を指定
 * 5. public $taxonomies に検索に利用するタクソノミーを指定
 * 6. public function filter_query の中身を調整
 *
 * # 命名規則
 * クラス名 : gm_{post_type}_controller
 * ファイル名 : {post_type}_controller
 */
class gm_post_controller extends gm_base_controller {

	private static $instance = [];

	/**
	 * フィルタをかける投稿タイプのスラッグ
	 * @var string
	 */
	public $post_type = "post";

	public $sort_options = [];

	/**
	 * 検索で利用するクエリパラメータを指定
	 * ex. http://localhost/products/?s_keyword=hogehoge
	 *     上記の場合は 【s_keyword】を指定
	 * @var string[]
	 */
	public $default_search_query_vars = [
		's_keyword'  => '', // 検索時の初期値を値として指定
//		's_hogehoge' => '' // 他にカスタムフィールドなどのキーがあれば指定
	];

	/**
	 * 検索で利用するタクソノミーを指定
	 * @var string[]
	 */
	public $taxonomies = [
		'category',
		'post_tags',
	];

	/**
	 * global $wp_query へのフィルタ
	 *
	 * @param $query WP_Query
	 *
	 * @return null
	 */
	public function filter_query( $query ) {

		/**
		 * 管理画面とメインクエリ以外は除外
		 */
		if ( is_admin() || ! $query->is_main_query() ) {
			return $query;
		}
		$this->query = $query;

		/**
		 * $this->post_typeで指定されている投稿タイプアーカイブの時に有効に
		 */
		if ( $this->is_filter_query_enabled() || $this->query->is_home() ) {

			/**
			 * カスタムフィールドに対して全文検索をかける場合は以下のコメントアウトを解除
			 */
			// add_filter( 'posts_search', [ $this, 'posts_search_custom_fields' ], 10, 2 );
			// add_filter( 'posts_join', [ $this, 'posts_join_custom_fields' ], 10, 2 );

			// 表示件数
			// $this->query->set( "posts_per_page", 2 );


			// $this->taxonomies で指定しているタクソノミーから tax_query を生成。
			// base_controller側でメソッドを定義しているが、複雑なqueryを組む場合はここを削除してtax_queryを指定すること
			$_tax_query = $this->get_tax_query( "AND", "term_id" );
			if ( $_tax_query ) {
				$this->query->set( "tax_query", $_tax_query );
			}

			/**
			 * カスタムフィールドに対する解除
			 */
			$meta_query = [];
			foreach ( $this->current_search_query_vars as $meta_key => $query ) {
				// keywordとつく検索クエリについては除外
				if ( mb_strpos( $meta_key, "keyword" ) !== false ) {
					continue;
				}
				$_meta_query = [
					// カスタムフィールドのキー。
					'key'     => $meta_key,
					// カスタムフィールドの値。配列を指定できるのは compare が 'IN', 'NOT IN', 'BETWEEN' または 'NOT BETWEEN' の場合です。WordPress 3.9 以上で compare に 'EXISTS' または 'NOT EXISTS' を指定する場合は value を省略できます。
					'value'   => $query,
					// テスト演算子。使える値は '=', '!=', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN', 'EXISTS' および 'NOT EXISTS'。デフォルトは '=' 。
					'compare' => "=",
					// カスタムフィールドの値のタイプ。使える値は 'NUMERIC', 'BINARY', 'CHAR', 'DATE', 'DATETIME', 'DECIMAL', 'SIGNED', 'TIME', 'UNSIGNED' です。デフォルトは 'CHAR' です。'DECIMAL' と 'NUMERIC' には有効桁数と小数点以下の桁数を指定できる（例: 'DECIMAL(10,5)' や 'NUMERIC(10)' が有効）。
					'type'    => 'CHAR',
				];
			}

			/**
			 * https://wpdocs.osdn.jp/%E9%96%A2%E6%95%B0%E3%83%AA%E3%83%95%E3%82%A1%E3%83%AC%E3%83%B3%E3%82%B9/WP_Query
			 */

			/**
			 * # 投稿ステータスでフィルタ
			 */
			// $this->query->set( "post_status", [ "publish" ] );

			/**
			 * # 並び替え
			 * 'none' - 順序をつけない（バージョン 2.8 以降で使用可能）。
			 * 'ID' - 投稿 ID で並び替える。大文字に注意。
			 * 'author' - 著者で並び替える。('post_author' でも良い。)
			 * 'title' - タイトルで並び替える。('post_title' でも良い。)
			 * 'name' - スラッグで並び替える。('post_name' でも良い。)
			 * 'type' - 投稿タイプで並び替える。('post_type' でも良い。)
			 * 'date' - 日付で並び替える。('post_date' でも良い。)
			 * 'modified' - 更新日で並び替える。('post_modified' でも良い。)
			 * 'parent' - 投稿/固定ページの親 ID 順。('post_parent' でも良い。)
			 * 'rand' - ランダムで並び替える。'RAND(x)' も使えます（'x' はシードになる整数）。
			 * 'comment_count' - コメント数で並び替える（バージョン 2.9 以降で使用可能）。
			 * 'relevance' - 文字列検索のとき次の順で並び替える: 1. 文字列全体がマッチ。 2. すべての単語がタイトルに含まれる。 3. いずれかの単語がタイトルに現れる。 4. 文字列全体が post_content に現れる。
			 * 'menu_order' - 固定ページの表示順で並び替える。固定ページ（固定ページ編集画面のページ属性ボックス）と添付ファイル（ギャラリー内のメディアの順番に相当）で使うことが最も多いでしょう。しかしバラバラの値が入った 'menu_order' を持つ任意の投稿タイプに対して使うことができます（デフォルト値は 0）。
			 * 'meta_value' - カスタムフィールドで並び替える。'meta_key=keyname' がクエリに存在しなければいけません。また、ソート順は文字列順になることに注意して下さい。数値だと予想外の挙動をします（通常、1, 3, 4, 6, 34, 56となると思うところが、1, 3, 34, 4, 56, 6となります）。数値なら代わりに 'meta_value_num' を使ってください。カスタムフィールドの値を特定の型にキャストしたければ 'meta_type' を指定できます。有効な値は 'NUMERIC', 'BINARY', 'CHAR', 'DATE', 'DATETIME', 'DECIMAL', 'SIGNED', 'TIME', 'UNSIGNED' です（'meta_query' と同じ）。'meta_type' を使うとき、それに応じて 'meta_value_*' も使えます。例えば 'meta_type' に DATETIME を指定するとき、ソート順の定義に 'meta_value_datetime' を使えます。
			 * 'meta_value_num' - カスタムフィールドの値を数値として並び替える。（バージョン 2.8 以降で使用可能）。これもまた、'meta_key=keyname'がクエリに存在しなければならないことに注意して下さい。こちらは 'meta_value' 示したような数値での並べ替えを可能にします。
			 * 'post__in' - post__in パラメータの配列に並んだ投稿 ID の順になります（バージョン 3.5 以降で利用可能）。
			 * 'post_name__in' - 'post_name__in' パラメータの配列に並んだ投稿スラッグの順になります（バージョン 4.6 以降で利用可能）。参考: このとき order パラメータの値はソート順を変えません。
			 * 'post_parent__in' - 'post_parent__in' パラメータの配列に並んだ親投稿 ID の順になります（バージョン 4.6 以降で利用可能）。参考: このとき order パラメータの値はソート順を変えません。
			 */
			// $this->query->set( "orderby", "date" );

			// # 並び替え : 配列で渡すとその順番で並び替えを行う
			// $this->query->set( "orderby", [ 'meta_value_num' => "ASC", 'modified' => 'DESC' ] );

			// # 並び替え時の照合順序を指定
			// $this->query->set( "order", "ASC" );

			// # 特定のIDを除外
			// $this->query->set( "post__not_in", [111,112] );

			// # 特定のIDのみ取得
			// $this->query->set( "post__in", [111,112] );

			// # 日付パラメータ
			// ex. 2021/12/12の記事を取得
			// $this->query->set( "date_query",[
			//     [
			//          'year' => 2021,
			//          'month' => 12,
			//          'day' => 12,
			//     ]
			// ] );

			// # 日付パラメータ : 特定の日よりあとの投稿を取得
			// ex. 2021/12/12以降の記事を取得
			// $this->query->set( "date_query",[
			//     'after' => [
			//          'year' => 2021,
			//          'month' => 12,
			//          'day' => 12,
			//     ]
			// ] );
			// ex. 2021/12/12以前の記事を取得
			// $this->query->set( "date_query",[
			//     'before' => [
			//          'year' => 2021,
			//          'month' => 12,
			//          'day' => 12,
			//     ]
			// ] );
			// ex. 2021/12/12から2021/12/13 以内の記事を取得
			// $this->query->set( "date_query",[
			//     'after' => [
			//          'year' => 2021,
			//          'month' => 12',
			//          'day' => 12',
			//     ]
			//     'before' => [
			//          'year' => 2021,
			//          'month' => 12,
			//          'day' => 13,
			//     ]
			// ] );

			/**
			 * # 投稿者パラメータ
			 */
			// $this->query->set( "author", 1); // ユーザー ID またはそのコンマ区切りリスト。
			// $this->query->set( "author_name", "growgroup"); //  'user_nicename' （姓・名・ニックネームではなく）。
			// $this->query->set( "author__in", [1]); // ユーザーID (バージョン 3.7 以降で利用可能)。
			// $this->query->set( "author__not_in", [1]); // ユーザーID (バージョン 3.7 以降で利用可能)。

			/**
			 * # 検索パラメータ
			 */
			// $this->query->set( "s", $this->current_search_query_vars["s_keyword"]);

		}

		return $this->query;
	}

}
