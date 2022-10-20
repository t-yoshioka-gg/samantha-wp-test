<?php

class GROWP_Yoast_SEO_Index_Clear{

    public static $instance = null;

    private function __construct(){
        add_action("admin_menu",[$this,"add_submenu"]);
    }
    public function add_submenu(){
        add_submenu_page("wpseo_dashboard", "インデックスをクリア", "インデックスをクリア", "manage_options", "index_clear", [$this, "page"]);
    }
    public function page(){
        $clear = false;
        if(isset($_POST["mode"]) && $_POST["mode"]==="index_clear" && wp_verify_nonce($_POST["_nonce"], __FILE__) ){
            $this->clear();
            $clear = true;
        }
        ?>
        <div class="wrap yoast wpseo-admin-page page-wpseo">
            <h1 id="wpseo-title">インデックスをクリア - Yoast SEO</h1>
            <p>
                データベースにインデックスされているキャッシュをクリアします。<br>
                強制的にクリアするため、パフォーマンスの低下が予想されます。<br>
                公開サイトで行う場合は十分注意してください。
            </p>
            <div class="wpseo_content_wrapper">
                <?php
                if($clear){
                    ?>
                    <div class="yoast-container yoast-container__configuration-wizard">
                        <div class="yoast-container__configuration-wizard--content"><h3>インデックスを削除しました。<span class="dashicons dashicons-yes"></span></h3><p>Webサイトの表示側を確認してください。</p></div></div>
                    <?php
                }
                ?>
                <form action="" method="post">
                    <input type="hidden" name="page" value="index_clear" />
                    <input type="hidden" name="mode" value="index_clear" />
                    <?php  wp_nonce_field(__FILE__, "_nonce"); ?>
                    <button type="submit" class="button button-primary">インデックスをクリアする</button>
                </form>
            </div><!-- end of div wpseo_content_wrapper -->
        </div>
        <?php
    }

    public static function get_instance() {
        if( ! static::$instance ){
            static::$instance = new self();
        }
        return static::$instance;
    }

	protected function clear() {
		global $wpdb;

		$r = $wpdb->query(
			$wpdb->prepare(
				'TRUNCATE TABLE %1$s',
				$wpdb->prefix ."yoast_indexable"
			)
		);


		$wpdb->query(
			$wpdb->prepare(
				'TRUNCATE TABLE %1$s',
                $wpdb->prefix ."yoast_indexable_hierarchy"
			)
		);
	}
}

add_action("after_setup_theme", function(){
    GROWP_Yoast_SEO_Index_Clear::get_instance();
});
