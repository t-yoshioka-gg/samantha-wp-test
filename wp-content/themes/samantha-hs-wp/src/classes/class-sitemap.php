<?php

/**
 * Class GROWP_Sitemap
 * サイトマップを出力するためのクラス
 *
 */

class GROWP_Sitemap
{
    /**
     * 設定
     * @var array
     */
    public $settings = array();

    /**
     * GROWP_Sitemap constructor.
     * 初期化
     *
     * @param $settings
     */
    public function __construct($settings)
    {


        $this->settings = wp_parse_args($settings, array(
            'active'            => array(
                'pages'            => true, // 固定ページの出力を有効に
                'posts'            => true, // 投稿の出力を有効に
                'custom_post_type' => true, // カスタム投稿タイプの出力を有効に
                'taxonomy'         => true, // タクソノミーの出力を有効に
            ),
            'posts_per_page' => 100, // 取得件数
            'exclude_ids'       => array(), // 除外するID
            'exclude_post_type' => array(), // 除外する投稿タイプ
            'exclude_taxonomy'  => array(), // 除外するタクソノミー
            'cache'             => true,    // キャッシュを有効にするか
            'transient_key' => "growp_sitemap", // Transient APIのキー
            'transient_expiration' => 60 * 60 * 24, // Transient APIの有効期限
        ));

        // キャッシュが有効な場合
        if ($this->settings["cache"]) {
            $cache_html = get_transient($this->settings["transient_key"]);
            if ($cache_html) {
                echo $cache_html;
                echo '<!-- GROWP_Sitemap from cache -->';
                return true;
            }
            ob_start();
        }

        if ($this->is_active_sitemap("pages")) {
            $this->pages();
        }
        if ($this->is_active_sitemap("custom_post_type")) {
            $this->custom_post_type();
        }
        if ($this->is_active_sitemap("posts")) {
            $this->posts();
        }
        if ($this->is_active_sitemap("taxonomy")) {
            $this->taxonomies();
        }
        if ($this->settings["cache"]) {
            $contents = ob_get_contents();
            ob_end_clean();
            set_transient($this->settings["transient_key"], $contents, $this->settings["transient_expiration"]);
            echo $contents;
            return true;
        }

    }

    /**
     * 有効なサイトマップか判断する
     *
     * @param $key
     *
     * @return bool
     */
    public function is_active_sitemap($key)
    {

        if ($this->settings["active"][$key]) {
            return true;
        }

        return false;
    }

    /**
     * CSSを出力する
     */
    public static function output_css()
    {
        ?>
        <style>
            .c-sitemap {
                margin-bottom: 40px;
            }

            .c-sitemap ul li {
                list-style: disc;
                list-style-position: inside;
            }

            .c-sitemap ul ul {
                margin-left: 20px;
            }

            .c-sitemap__title {
                font-weight: bold;
                font-size: 20px;
                border-bottom: 1px solid #000;
                padding-bottom: 8px;
                margin-bottom: 10px;
            }
        </style>
        <?php
    }

    /**
     * サイトマップを出力する
     * @param array $settings
     *
     * @return GROWP_Sitemap
     */
    public static function output($settings = array())
    {
        return new self($settings);
    }

    /**
     * li タグを出力
     *
     * @param $link
     * @param $text
     */
    public function li($link, $text)
    {
        echo "<li><a href='$link'>$text</a></li>";
    }

    /**
     * ラッパー開始タグを出力
     */
    public function before()
    {
        echo '<div class="c-sitemap">';
    }

    /**
     * ラッパー閉じタグを出力
     */
    public function after()
    {
        echo '</div>';
    }

    /**
     * 各サイトマップのタイトル
     *
     * @param $text
     */
    public function title($text)
    {
        echo '<div class="c-sitemap__title">' . $text . "</div>";
    }

    /**
     * カスタム投稿タイプ
     */
    public function custom_post_type()
    {
        // 除外する投稿タイプ
        $_ignores = array(
            'post',
            'page',
            'attachment',
            'nav_menu_item',
            'revision',
            'custom_css',
            'customize_changeset'
        );

        $ignores = array_merge($this->settings["exclude_post_type"], $_ignores);

        $_post_types = get_post_types();

        array_map(function ($i) use (&$_post_types, $ignores) {
            if (in_array($i, $ignores)) {
                unset($_post_types[$i]);
            }

            return $i;
        }, $_post_types);

        foreach ($_post_types as $post_type) {
            $post_type_object = get_post_type_object($post_type);
            if ( ! $post_type_object->public) {
                continue;
            }

            $labels = get_post_type_labels($post_type_object);
            $posts  = get_posts(array(
                'post_type'      => $post_type,
                'posts_per_page' => $this->settings["posts_per_page"]
            ));
            $this->before();
            $this->title($labels->name);
            echo "<ul>";
            foreach ($posts as $post) {
                $this->li(get_the_permalink($post->ID), get_the_title($post->ID));
            }
            echo "</ul>";
            $this->after();
        }
    }

    /**
     * タクソノミー
     */
    public function taxonomies()
    {
        global $wp_taxonomies;

        foreach ($wp_taxonomies as $tax) {
            if (in_array($tax->name, $this->settings["exclude_taxonomy"], true)) {
                continue;
            }
            if ( ! $tax->public) {
                continue;
            }
            if ($tax->name === "post_format") {
                continue;
            }
            $this->before();
            $this->title($tax->labels->name);
            echo "<ul>";
            wp_list_categories(array('taxonomy' => $tax->name, "title_li" => false));
            echo "</ul>";
            $this->after();
        }
    }

    /**
     * 投稿
     */
    public function posts()
    {
        $post_type_object = get_post_type_object("post");
        if ( ! $post_type_object->public) {
            return false;
        }
        $labels = get_post_type_labels($post_type_object);
        $posts  = get_posts(array(
            'post_type'      => "post",
            'posts_per_page' => $this->settings["posts_per_page"]
        ));
        $this->before();
        $this->title($labels->name);
        echo "<ul>";
        foreach ($posts as $post) {
            if ($this->check_excluded_id($post->ID)) {
                continue;
            }
            $this->li(get_the_permalink($post->ID), get_the_title($post->ID));
        }
        echo "</ul>";
        $this->after();

    }

    /**
     * 除外IDかチェック
     *
     * @param $id
     *
     * @return bool
     */
    public function check_excluded_id($id)
    {
        if (in_array($id, $this->settings["exclude_ids"], true)) {
            return true;
        }

        return false;
    }


    /**
     * 固定ページを一覧表示
     * @return bool
     */
    public function pages()
    {
        function recursive($p_id, $page, $self)
        {
            if ($self->check_excluded_id($p_id)) {
                return false;
            }
            $children           = get_pages(array(
                "child_of"    => $p_id,
                "sort_column" => "menu_order",
            ));
            $immediate_children = get_pages(array(
                "child_of"    => $p_id,
                "parent"      => $p_id,
                "sort_column" => "menu_order"
            ));
            if ($children) {
                echo
                    '<li>
                        <a href="' . get_the_permalink($page->ID) . '">
                            <span class="first-level">' . $page->post_title . '</span>
                        </a>
                    <ul>';
                foreach ($immediate_children as $child) {
                    recursive($child->ID, $child, $self);
                }
                echo '</ul></li>';
            } else {
                echo '<li class="page_item page-item-' . $page->ID . '"><a href="' . get_page_link($page->ID) . '">' . $page->post_title . '</a></li>';
            }
        }

        $this->before();
        $this->title(get_bloginfo("name"));
        echo "<ul>";
        $top_level_pages = get_pages(array('parent' => 0, "sort_column" => "menu_order"));
        foreach ($top_level_pages as $page) {
            $p_id = $page->ID;
            recursive($p_id, $page, $this);
        }
        echo "</ul>";
        $this->after();

        return;
    }
}
