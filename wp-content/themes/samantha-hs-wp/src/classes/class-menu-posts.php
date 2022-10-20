<?php
/**
 * メニューで作成した一覧を取得する
 */

// 1. 登録のサンプル
// add_action( "registered_taxonomy", function () {
//	new GROWP_MenuPosts( 'sales_posts', 'セールス' );
//	new GROWP_MenuPosts( 'featured_posts', '特集' );
// } );
// 2. 利用時のサンプル
// 

class GROWP_MenuPosts
{

    // メニューのロケーション
    public $location = "";

    // メニューのオブジェクト
    public $menus = null;

    /**
     * 初期化
     *
     * @param $location
     * @param $name
     */
    public function __construct($location, $name)
    {

        $this->location = $location;
        $this->name     = $name;

        // メニューにセールス用を追加
        add_action('init', array($this, 'register_menu'));
        add_shortcode('menu_posts', array($this, 'register_shortcode'));
    }

    /**
     * メニューを登録
     * @return void
     */
    public function register_menu()
    {
        register_nav_menu($this->location, $this->name);
    }


    /**
     * 投稿一覧を取得
     * @return void
     */
    public function set_menus()
    {
        $locations = get_nav_menu_locations();

        if (empty($locations[$this->location])) {
            return false;
        }
        $menu = wp_get_nav_menu_object($locations[$this->location]);

        $this->menus = wp_get_nav_menu_items($menu->term_id, array('update_post_term_cache' => false));

    }

    /**
     * 再帰
     */
    public static function createTree(&$list, $parent)
    {
        $tree = array();
        foreach ($parent as $k => $l) {
            if (isset($list[$l->ID])) {
                $l->children = self::createTree($list, $list[$l->ID]);
            }
            $tree[] = $l;
        }
        return $tree;
    }

    /**
     * 投稿一覧を取得
     * @return void
     */
    public function get_menus()
    {
        $this->set_menus();
        $parse_menus = array();

        if ( ! is_array($this->menus)) {
            return false;
        }

        // ツリー構造のメニューを整形する
        foreach ($this->menus as $menu) {
            $parse_menus[$menu->menu_item_parent][] = $menu;
        }

        $parse_menus = self::createTree($parse_menus, $parse_menus[0]);
        return $parse_menus;
    }

    /**
     * レンダリング
     * @return void
     */
    public function render()
    {
        $this->set_menus();
        if (empty($this->menus)) {
            return false;
        }
        foreach ($this->menus as $menu) {
            global $post;
            $post = get_post($menu->object_id);
            setup_postdata($post);
            GTemplate::get_project( "post-item");
        }
    }

    /**
     * ショートコードとして呼び出せるように
     *
     * @param string $content
     * @param array $attrs
     *
     * @return string
     */
    public function register_shortcode($content = "", $attrs = array())
    {
        ob_start();
        $this->render();
        $content = ob_get_contents();
        ob_clean();

        return $content;
    }
}
