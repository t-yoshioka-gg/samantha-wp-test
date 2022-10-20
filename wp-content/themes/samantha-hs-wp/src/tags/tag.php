<?php

/**
 * Class GTag
 * オリジナルテンプレートタグ
 */
class GTag
{

	/**
	 * サムネイル画像のURLを取得
	 *
	 * @param bool $post_id
	 * @param string $size
	 *
	 * @return false|string
	 */
	public static function get_thumbnail_url($post_id = false, $size = "full")
	{
		if (!$post_id) {
			$post_id = get_the_ID();
		}
		$thumbnail_id = get_post_meta($post_id, "_thumbnail_id", true);
		$imageurl = wp_get_attachment_image_url($thumbnail_id, $size);
		if ($imageurl) {
			return $imageurl;
		}

		return GUrl::asset("/assets/images/img-default-thumbnail.jpg");
	}


	/**
	 * サムネイル画像のstyle属性を出力
	 *
	 * @param bool $post_id
	 * @param string $size
	 *
	 * @return string
	 */
	public static function the_thumbnail_style_attribute($post_id = false, $size = "full")
	{
		$url = self::get_thumbnail_url($post_id, $size);

		$attr = "";
		$attr = ' style="background-image: url(' . $url . ');"';
		echo $attr;
	}

	/**
	 * 最初のタームを取得する
	 *
	 * @param bool $post_id 投稿のID
	 * @param bool $taxonomy タクソノミー
	 * @param string $field 取得したいフィールド
	 *
	 * @return bool
	 */
	public static function get_first_term($post_id = false, $taxonomy = false, $field = "")
	{

		if (!$post_id) {
			$post_id = get_the_ID();
		}

		$terms = get_the_terms($post_id, $taxonomy);
		if (is_wp_error($terms)) {
			return false;
		}

		if (!empty($field) && isset($terms[0]->{$field})) {
			return esc_html($terms[0]->{$field});
		}

		return $terms[0];
	}


	/**
	 * アーカイブのタイトルを取得する
	 *
	 * @return string|void
	 */
	public static function get_archive_title()
	{
		if (is_category()) {
			$title = sprintf(__('Category: %s'), single_cat_title('', false));
		} elseif (is_tag()) {
			$title = sprintf(__('Tag: %s'), single_tag_title('', false));
		} elseif (is_author()) {
			$title = sprintf(__('Author: %s'), '<span class="vcard">' . get_the_author() . '</span>');
		} elseif (is_year()) {
			$title = sprintf(__('Year: %s'), get_the_date(_x('Y', 'yearly archives date format')));
		} elseif (is_month()) {
			$title = sprintf(__('Month: %s'), get_the_date(_x('F Y', 'monthly archives date format')));
		} elseif (is_day()) {
			$title = sprintf(__('Day: %s'), get_the_date(_x('F j, Y', 'daily archives date format')));
		} elseif (is_tax('post_format')) {
			if (is_tax('post_format', 'post-format-aside')) {
				$title = _x('Asides', 'post format archive title');
			} elseif (is_tax('post_format', 'post-format-gallery')) {
				$title = _x('Galleries', 'post format archive title');
			} elseif (is_tax('post_format', 'post-format-image')) {
				$title = _x('Images', 'post format archive title');
			} elseif (is_tax('post_format', 'post-format-video')) {
				$title = _x('Videos', 'post format archive title');
			} elseif (is_tax('post_format', 'post-format-quote')) {
				$title = _x('Quotes', 'post format archive title');
			} elseif (is_tax('post_format', 'post-format-link')) {
				$title = _x('Links', 'post format archive title');
			} elseif (is_tax('post_format', 'post-format-status')) {
				$title = _x('Statuses', 'post format archive title');
			} elseif (is_tax('post_format', 'post-format-audio')) {
				$title = _x('Audio', 'post format archive title');
			} elseif (is_tax('post_format', 'post-format-chat')) {
				$title = _x('Chats', 'post format archive title');
			}
		} elseif (is_post_type_archive()) {
			$title = sprintf(__('Archives: %s'), post_type_archive_title('', false));
		} elseif (is_tax()) {
			$tax = get_taxonomy(get_queried_object()->taxonomy);
			/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
			$title = sprintf(__('%1$s: %2$s'), $tax->labels->singular_name, single_term_title('', false));
		} else {
			$title = __('Archives');
		}

		return $title;

	}

	/**
	 * 親ページを判断する
	 *
	 * @param $slug 判定したい固定ページのスラッグ
	 * @param $post_id 判定対象としたい記事ID
	 * @return bool
	 */
	public static function is_parent_page($slug, $post_id = 0)
	{
		if (!$post_id) {
			$post_id = get_the_ID();
		}
		$return = false;
		if (is_string($slug)) {
			$return = self::_is_parent_page($slug, $post_id);
		}
		if (is_array($slug)) {
			foreach ($slug as $s) {
				$return = self::_is_parent_page($s, $post_id);
				if ($return === true) {
					break;
				}
			}
		}

		return $return;
	}


	/**
	 * 再帰関数
	 * GTag::is_parent_page()内で利用
	 */
	private static function _is_parent_page( $slug, $post_id = 0 ) {
		if ( ! $post_id ) {
			global $post;
			if ( ! $post ) {
				return false;
			}
			$post_id = $post->ID;
		} else {
			$post = get_post( $post_id );
		}

		if ( ! $post ) {
			return false;
		}

		if ( $post->post_name === $slug ) {
			return true;
		}
		if ( ! $post->post_parent ) {
			return false;
		}
		$parent_post = get_post( $post->post_parent );
		if ( $parent_post->post_name === $slug ) {
			return true;
		}

		return self::_is_parent_page( $slug, $parent_post->ID );
	}

	/**
	 * テンプレートのパスを取得する
	 * @return mixed
	 */
	public static function get_template_path()
	{
		return GROWP_Theme_Wrapper::$main_template;
	}

	/**
	 * @return mixed
	 */
	public static function get_template_base()
	{
		return GROWP_Theme_Wrapper::$base;
	}


	/**
	 * 投稿に対して、親カテゴリを指定し、その親タームに属するタームが設定されているかどうか判断する
	 *
	 * @param $post_id
	 * @param $term
	 * @param string $taxonomy
	 *
	 * @return bool
	 */
	public static function post_in_parent_term($post_id, $term, $taxonomy = "category")
	{

		$terms = get_the_terms($post_id, $taxonomy);

		if (is_wp_error($terms)) {
			echo $terms->get_error_message();
			exit;
		}
		if (is_string($term)) {
			$term = get_term_by("slug", $term, $taxonomy);
		}
		$check_term = get_term($term);

		foreach ($terms as $_term) {
			if ($_term->term_id === $check_term->term_id) {
				return true;
			}
			$parent_term = self::_post_check_term($_term, $taxonomy);
			if ($parent_term->slug === $_term->slug) {
				return true;
			}
		}

		return false;
	}

	/**
	 * 再帰関数
	 *
	 * @param $term
	 * @param $taxonomy
	 *
	 * @return mixed
	 */
	public static function _post_check_term($term, $taxonomy)
	{
		if ($term->parent) {
			$_term = get_term($term->parent, $taxonomy);
			$return = self::_post_check_term($_term, $taxonomy);
		} else {
			$return = $term;
		}

		return $return;
	}

	/**
	 * アタッチメントのURLを取得する
	 *
	 * @param $attachment_id アタッチメントID
	 * @param string $size
	 *
	 * @return string 存在する場合はファイルのURL。しない場合はデフォルト画像
	 */
	public static function get_attachment_url($attachment_id, $size = "full")
	{
		$image_url = wp_get_attachment_image_url($attachment_id, $size, false);

		if ($image_url) {
			return $image_url;
		}

		return GUrl::asset("/assets/images/img-default-thumbnail.jpg");
	}

	/**
	 * スマートフォンかどうか判断する
	 * @return bool
	 */
	public static function is_smartphone()
	{
		$mb = new \Mobile_Detect();

		return ($mb->isMobile() && !$mb->isTablet());
	}

	/**
	 * 投稿に設定されている特定のタクソノミーのタームのリストを出力する
	 *
	 * @param int $post_id 投稿ID
	 * @param string $taxonomy タクソノミー
	 *
	 * @return string
	 */
	public static function get_the_terms_label_list($post_id = 0, $taxonomy = "category")
	{

		if (!$post_id) {
			$post_id = get_the_ID();
		}
		$terms = get_the_terms($post_id, $taxonomy);
		$list = "<ul>";
		foreach ($terms as $t) {
			$list .= '<li><a href="' . get_term_link($t, $taxonomy) . '" class="c-label">' . $t->name . '</a></li>';
		}
		$list .= "</ul>";

		return $list;
	}


	/**
	 * タクソノミーアーカイブページにて、
	 * 現在表示しているタームを親カテゴリを含めて判断する
	 *
	 * @param $parent 含まれているかどうか判断したい親ターム
	 * @param string $taxonomy タクソノミー
	 *
	 * @return bool true: 親タームに属する false: 属さない
	 */
	public static function archive_in_parent_term($parent, $taxonomy = "category")
	{
		global $wp_query;
		if (empty($wp_query->queried_object->term_id)) {
			return false;
		}

		/**
		 * IDではない場合にスラッグからterm_idを取得する
		 */
		if (!is_numeric($parent)) {
			$_term = get_term_by("slug", $parent, $taxonomy);
			$parent = $_term->term_id;
		}

		if ($wp_query->queried_object->term_id === $parent) {
			return true;
		}
		$current_term_parent = $wp_query->queried_object->parent;

		if ($current_term_parent) {
			$is_parent = self::archive_check_term_parent($parent, $current_term_parent, $taxonomy);
		}

		return $is_parent;
	}

	/**
	 * 再帰
	 *
	 * @param $targetparent
	 * @param $term
	 * @param $taxonomy
	 *
	 * @return bool
	 */
	public static function archive_check_term_parent($targetparent, $term, $taxonomy)
	{
		$_term = get_term($term, $taxonomy);

		if ($targetparent === $_term->term_id) {
			return true;
		}
		$return = false;
		if ($_term->parent) {
			$return = self::archive_check_term_parent($targetparent, $_term->parent, $taxonomy);
		}

		return $return;
	}

	/**
	 * タームのラベルを出力する
	 *
	 * @param string $taxonomy
	 */
	public static function term_label_list($taxonomy = "category")
	{
		$post_id = get_the_ID();
		$terms = wp_get_post_terms($post_id, $taxonomy, array('fields' => "all"));
		foreach ($terms as $term_key => $term) {
			?>
			<div class="p-post__category"><span class="c-label is-primary"><?php echo $term->name ?></span>
			</div>
			<?php
		}
	}

	/**
	 * タームのリンク付きラベル一覧を出力する
	 *
	 * @param string $taxonomy タクソノミースラッグ
	 */
	public static function category_label_links($taxonomy = "category")
	{
		$post_id = get_the_ID();
		$terms = wp_get_post_terms($post_id, $taxonomy, array('fields' => "all"));
		foreach ($terms as $term_key => $term) {
			?>
			<div class="p-post__category">
				<a href="<?php echo get_term_link($term) ?>" class="c-label is-primary"><?php echo $term->name ?></a>
			</div>
			<?php
		}
	}

	/**
	 * 文字列を丸める(mb_strimwidthのエイリアス)
	 *
	 * @param string $text 加工する文字列
	 * @param integer $length 丸める文字列
	 * @param string $after 付与する文字列
	 *
	 * @return 文字列
	 *
	 */
	public static function trimwidth($text, $length = 50, $after = "...")
	{
		return mb_strimwidth($text, 0, $length, $after);
	}

	/**
	 * ACFフィールドの出力
	 *
	 * @param $key string | array 配列で指定した場合、どちらかが存在する場合に出力する
	 * @param $callback
	 * @param $post_id
	 *
	 * @return bool|void
	 *
	 * @throws ErrorException AdvancedCustomFields プラグインがインストールされていない場合には例外を投げる
	 */
	public static function acf($key, $callback = null, $post_id = 0)
	{
		if (!function_exists('get_field')) {
			throw new ErrorException("500", "Advanced Custom Fields プラグインがインストールされていません");
			exit;
		}
		if (is_numeric($callback)) {
			$post_id = $callback;
		}
		if (is_callable($post_id)) {
			$callback = $post_id;
		}
		if ($post_id === 0) {
			$post_id = get_the_ID();
		}
		// グループに対応
		$value = get_field($key, $post_id);
		if (!$value && strpos($key, ".", 1) > -1) {
			$keys = explode(".", $key);
			$value = "";
			foreach ($keys as $kk => $k) {
				if ($value && isset($value[$k])) {
					$value = $value[$k];
				} else {
					$value = "";
				}
				if (!$value) {
					$value = get_field($keys[$kk], $post_id);
				}
			}
		}
		/**
		 * キーが配列指定の場合
		 */
		if (is_array($key)) {
			$value = [];
			$exitflag = false;
			foreach ($key as $kk => $k) {
				$value[$k] = get_field($k, $post_id);
				if ($value[$k]) {
					$exitflag = true;
				}
			}
			if (!$exitflag) {
				return false;
			}
		}
		if ($value && is_callable($callback)) {
			return $callback($value);
		}

		return $value;
	}

	/**
	 * WordPress SEO のプライマリカテゴリを取得する
	 *
	 * @param string $taxonomy
	 *
	 * @return bool|int
	 */
	public static function get_primary_term($taxonomy = "category")
	{
		if (!class_exists("WPSEO_Primary_Term")) {
			return false;
		}
		$p = new WPSEO_Primary_Term($taxonomy, get_the_ID());
		if (!$p->get_primary_term()) {
			$terms = get_the_terms(get_the_ID(), $taxonomy);
			if (is_wp_error($terms)) {
				return false;
			}

			return (isset($terms[0])) ? $terms[0] : false;
		}

		return $p->get_primary_term();
	}

	/**
	 * 親のタームを取得
	 *
	 * @param $term
	 * @param $taxonomy
	 *
	 * @return WP_Term
	 */
	public static function get_parent_term($term, $taxonomy = "category")
	{
		if ($term->parent === 0) {
			return $term;
		}

		return self::get_parent_term(get_term($term->parent, $taxonomy), $taxonomy);
	}

	/**
	 * ページの階層を取得
	 *
	 * @param int $post_id
	 *
	 * @return int
	 */
	public static function get_page_level($post_id = 0)
	{
		if (!$post_id) {
			global $post;
			$post_id = $post->ID;
		}
		// 投稿タイプが階層対応していない場合は0を返す
		$post_type = get_post_type($post_id);
		$pt_obj = get_post_type_object($post_type);
		if (!$pt_obj->hierarchical) {
			return 0;
		}
		$levels = get_post_ancestors($post_id);

		return count($levels);
	}


	/**
	 * YouTubeのURLからビデオIDを取得する
	 *
	 * @param $url
	 *
	 * @return mixed|string
	 */
	public static function get_youtube_video_id($url)
	{
		$match = array();
		preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
		if (isset($match[1])) {
			return $match[1];
		}

		return "";
	}

	/**
	 * 指定件数分の記事を取得する
	 *
	 * @param $post_number
	 * @param string $post_type
	 * @param string $orderby
	 * @param string $exclude
	 *
	 * @return int[]|WP_Post[]
	 */
	public static function get_posts_number($post_number, $post_type = "post", $orderby = "", $exclude = "")
	{
		$args = array(
				"post_type" => $post_type,
				'post_status' => 'publish',
				'posts_per_page' => $post_number
		);

		if (!empty($orderby)) {
			$args["orderby"] = $orderby;
		}
		if (!empty($exclude)) {
			$args["exclude"] = $exclude;
		}

		return self::get_posts($args);
	}

	/**
	 * 特定カテゴリーに属する記事を取得する
	 *
	 * @param $category_id
	 * @param $post_number
	 * @param string $post_type
	 * @param string $taxonomy
	 * @param string $orderby
	 * @param string $exclude
	 *
	 * @return int[]|WP_Post[]
	 */
	public static function get_posts_category($category_id, $post_number, $post_type = "post", $taxonomy = "category", $orderby = "", $exclude = "")
	{
		$args = array(
				"post_type" => $post_type,
				'post_status' => 'publish',
				'posts_per_page' => $post_number,
				'tax_query' => array(
						array(
								'taxonomy' => $taxonomy,
								'field' => 'term_id',
								'terms' => array($category_id),
								'include_children' => true,
						),
				),
		);
		if (!empty($orderby)) {
			$args["orderby"] = $orderby;
		}
		if (!empty($exclude)) {
			$args["exclude"] = $exclude;
		}

		return self::get_posts($args);
	}


	/**
	 * すべてのタームを取得する
	 *
	 * @param $taxonomies
	 * @param string $orderby
	 * @param bool $is_root_only
	 *
	 * @return array|int|WP_Error
	 */
	public static function get_all_term($taxonomies, $orderby = "", $is_root_only = false)
	{

		$args = array(
				'hide_empty' => false,
		);
		if (!empty($orderby)) {
			$args["orderby"] = $orderby;
		}

		if ($is_root_only) {
			$args["parent"] = 0;
		}

		return get_terms($taxonomies, $args);

	}

	/**
	 * カスタムフィールドから記事を取得する
	 *
	 * @param $meta_key
	 * @param $meta_value
	 * @param string $compare
	 * @param string $post_type
	 * @param int $post_number
	 *
	 * @return int[]|WP_Post[]
	 */
	public static function get_posts_by_meta_field($meta_key, $meta_value, $compare = "=", $post_type = "post", $post_number = 99999)
	{
		$args = array(
				"post_type" => $post_type,
				'post_status' => 'publish',
				'posts_per_page' => $post_number,
				'meta_query' => array(
						array(
								'key' => $meta_key,
								'value' => $meta_value,
								'compare' => $compare,
						),
				),
		);

		return self::get_posts($args);
	}

	/**
	 * 記事取得のラップメソッド
	 *
	 * @param $args
	 *
	 * @return int[]|WP_Post[]
	 */
	public static function get_posts($args)
	{

		$defaults = array(
				'posts_per_page' => 6,
				'category' => 0,
				'orderby' => 'date',
				'order' => 'DESC',
				'include' => array(),
				'exclude' => array(),
				'meta_key' => '',
				'meta_value' => '',
				'post_type' => 'post',
				'suppress_filters' => true,
				'offset' => 0,
				'category_name' => '',
				'post_mime_type' => '',
				'post_parent' => '',
				'author' => '',
				'post_status' => 'publish',
		);

		$args = wp_parse_args($args, $defaults);

		return get_posts($args);
	}

	public static function get_parent_page_id($page_id)
	{
		$page = get_post($page_id);
		if (isset($page->post_parent) && $page->post_parent !== 0) {
			return static::get_parent_page_id($page->post_parent);
		}

		return $page_id;
	}

	public static function get_child_pages($parent_page_id)
	{
		$pages = get_pages([
				'parent' => $parent_page_id,
				'sort_column' => 'menu_order',
		]);

		return $pages;
	}

	public static function generate_subnav($path)
	{
		$_page = get_page_by_path($path);
		$pages = GTag::get_child_pages($_page->ID);
		foreach ($pages as $page) {
			$list_image = get_field("p_list_image", $page->ID);
			?>
			<li>
				<a href="<?php echo get_the_permalink($page->ID) ?>">
					<img src="<?php echo GTag::get_attachment_url($list_image, "full") ?>" alt=""/>
					<span><?php echo $page->post_title ?></span></a>
			</li>
			<?php
		}
	}

	public static function generate_subnav_list($path)
	{
		$_page = get_page_by_path($path);
		$pages = GTag::get_child_pages($_page->ID);
		foreach ($pages as $page) {
			?>
			<li>
				<a href="<?php echo get_the_permalink($page->ID) ?>"><?php echo $page->post_title ?></a>
			</li>
			<?php
		}
	}

	public static function insert_attachment_from_url($url, $parent_post_id = null)
	{

		if (!class_exists('WP_Http')) {
			include_once(ABSPATH . WPINC . '/class-http.php');
		}

		$http = new WP_Http();
		$response = $http->request($url);
		if ($response['response']['code'] != 200) {
			return false;
		}

		$upload = wp_upload_bits(basename($url), null, $response['body']);
		if (!empty($upload['error'])) {
			return false;
		}

		$file_path = $upload['file'];
		$file_name = basename($file_path);
		$file_type = wp_check_filetype($file_name, null);
		$attachment_title = sanitize_file_name(pathinfo($file_name, PATHINFO_FILENAME));
		$wp_upload_dir = wp_upload_dir();

		$post_info = array(
				'guid' => $wp_upload_dir['url'] . '/' . $file_name,
				'post_mime_type' => $file_type['type'],
				'post_title' => $attachment_title,
				'post_content' => '',
				'post_status' => 'inherit',
		);

		$attach_id = wp_insert_attachment($post_info, $file_path, $parent_post_id);
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		$attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
		wp_update_attachment_metadata($attach_id, $attach_data);

		return $attach_id;
	}


	public static function get_link_url()
	{
		$post_url = get_permalink();
		$link_data = get_field("c_links");

		if (!$link_data || $link_data["type"] == "normal") {
			// 投稿記事を表示
		} else if ($link_data["type"] == "file") {
			$file = wp_get_attachment_url($link_data["link_file"]);
			if ($file) {
				return $file;
			}
		} else if ($link_data["type"] == "url") {
			if ($link_data["link_url"]) {
				return $link_data["link_url"];
			}
		}

		return $post_url;
	}

	public static function is_info_link_enable()
	{
		$link_data = get_field("c_links");

		if (!$link_data || $link_data["type"] == "none") {
			return false;
		}

		return true;
	}

	public static function get_link_target()
	{
		$target = "_self";
		$link_data = get_field("c_links");

		if (!$link_data || $link_data["type"] == "normal") {
			// 投稿記事を表示
		} else if ($link_data["type"] == "file") {
			$file = wp_get_attachment_link($link_data["link_file"]);
			if ($file) {
				$target = "_blank";
			}
		} else if ($link_data["type"] == "url") {


			if (self::is_file_link($link_data["link_url"])) {
				$target = "_blank";
			}
			if (self::is_external_link($link_data["link_url"])) {
				$target = "_blank";
			}
		}

		return $target;
	}

	public static function is_external_link($url)
	{
		if (strpos($url, get_site_url()) !== false // 自社サイトのURLが含まれていれば内部リンク
				|| strpos($url, "://") === false) { // ://が含まれていなければ内部リンク

			return false;
		} else {
			return true;
		}

	}

	public static function is_external_info()
	{
		$result = false;
		$link_data = get_field("c_links");
		if (!$link_data || $link_data["type"] == "normal") {

		} else if ($link_data["type"] == "file") {
			$file = wp_get_attachment_link($link_data["link_file"]);
			if ($file) {
				$result = true;
			}
		} else if ($link_data["type"] == "url") {
			if ($link_data["link_url"]) {
				$result = true;
			}
		}

		return $result;
	}


	public static function is_file_link($url)
	{
		$type = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'xls', 'doc', 'xls', 'docx', 'xlsx');

		$ext = explode(".", $url);
		if ($ext && count($ext) > 0 && in_array($ext[count($ext) - 1], $type)) {
			return true;
		} else {
			return false;
		}
	}

	public static function get_link_label()
	{

		$label = " ページを表示 ";
		$link_data = get_field("c_links");
		if ($link_data["type"] == "file") {
			$label = " ファイルを表示 ";
		} else if ($link_data["type"] == "url") {
			$url = self::get_link_url();

			if (self::is_file_link($url)) {
				$label = " ファイルを表示 ";
			}
		}
		$label .= GTag::get_link_icon();

		return $label;
	}

	public static function get_link_icon()
	{
		$link_data = get_field("c_links");
		$icon = "";
		if (!$link_data) {
			return "";
		}
		if ($link_data["type"] == "url") {
			if ($link_data["link_url"] && self::is_external_link($link_data["link_url"])) {
				$icon = "fa-external-link";
			}
		}
		if ($link_data["type"] == "file") {
			if (!$link_data || empty($link_data["icon"])) {
				return "";
			}
			$icon = $link_data["icon"];
		}
		if (empty($icon)) {
			return "";
		}

		return '<i class="fa ' . $icon . '" aria-hidden="true"></i>';
	}

	/**
	 * フォーム対象ページを判断する
	 *
	 * @return bool
	 */
	public static function is_form() {

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		//MW WP FORMの有効判定
		if ( is_plugin_active( 'mw-wp-form/mw-wp-form.php' ) ) {
			global $post;
			if ( isset( $post ) ) {
				$contents = get_the_content( $post->ID );
				if ( preg_match( "/\[mwform_formkey/", $contents ) ) {
					return true;
				}
			}
		}

		if ( is_page( "contact" ) || GTag::is_parent_page( "contact" ) ) {
			return true;
		}

		return false;
	}
}
