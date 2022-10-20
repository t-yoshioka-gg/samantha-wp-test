<?php
/**
 * 管理画面周りのフック
 */
/**
 * ログイン画面の<head>内に出力
 */
function growp_login_head() {
	?>
    <style>
        h1 a {
            width: 290px !important;
            background-image: url(<?php GUrl::the_asset() ?>/assets/images/logo.png) !important;
            height: 44px !important;
            background-size: contain !important;
            background-position: center center !important;
            image-rendering: -webkit-optimize-contrdast;
        }
    </style>
	<?php
}

//add_action( "login_head", 'growp_login_head' );

/**
 * 管理バーのロゴを非表示に
 * @return string|void
 */
function remove_admin_logo() {
	if ( ! is_user_logged_in() ) {
		return "";
	}
	echo '<style>
	#wp-admin-bar-wp-logo{ display: none; }
	img.blavatar { display: none;}
	#wpadminbar .quicklinks li div.blavatar {display:none;}
	#wpadminbar .quicklinks li .blavatar {display:none;}
	/*#wpadminbar #wp-admin-bar-new-content .ab-icon:before {display:none;}*/
	#wpadminbar .quicklinks li .blavatar:before {display:none;}
	</style>';
}

add_action( 'admin_head', 'remove_admin_logo' );
add_action( 'wp_head', 'remove_admin_logo' );


/**
 * 全ユーザーの管理画面に適用されるcss
 */
function growp_for_common_admin_style() {
	?>
    <style>

    </style>
	<?php
}

add_action( "admin_head", "growp_for_common_admin_style" );


/**
 * 編集者アカウントの管理画面に適用されるcss
 */
function growp_for_common_editor_style() {
	if ( ! current_user_can( "editor" ) ) {
	    return "";
	}
	?>
    <style>

    </style>
	<?php
}

add_action( "admin_head", "growp_for_common_editor_style" );


/**
 * 管理画面の氏名の順番を変更
 */
function growp_lastfirst_name() {
	?>
    <script>
        (function ($) {
            $(function () {
                $('#last_name').closest('tr').after($('#first_name').closest('tr'));

                $(".user-rich-editing-wrap").closest("table").hide();
                $(".user-description-wrap").closest("table").hide();
                $(".user-url-wrap").hide();
                $(".user-facebook-wrap").hide();
                $(".user-instagram-wrap").hide();
                $(".user-linkedin-wrap").hide();
                $(".user-myspace-wrap").hide();
                $(".user-pinterest-wrap").hide();
                $(".user-soundcloud-wrap").hide();
                $(".user-tumblr-wrap").hide();
                $(".user-twitter-wrap").hide();
                $(".user-youtube-wrap").hide();
                $(".user-wikipedia-wrap").hide();
                $("h2:contains(ユーザーについて)").hide();
                $("h2:contains(連絡先情報)").hide();
                $("h2:contains(アカウント管理)").hide();
                $(".yoast.yoast-settings.postbox").hide();
                $("#edit-slug-box").hide();
                $(".form-field.user-language-wrap").hide();
                $("label:contains(サイト)").closest("tr").hide();

                $("#send_user_notification").prop("checked", false);
                var $email_tr = $("#email").closest("tr");
                $("#user_login").closest("tr").before($email_tr)
                $(document).on("change", "#email", function () {
                    var e = $(this).val();
                    var _e = e.split("@");
                    $("#user_login").val(_e[0])
                })
            });
        })(jQuery);
    </script><?php
}


add_action( 'admin_footer-user-new.php', 'growp_lastfirst_name' );
add_action( 'admin_footer-user-edit.php', 'growp_lastfirst_name' );
add_action( 'admin_footer-profile.php', 'growp_lastfirst_name' );


function growp_admin_head_post_profile() {
	?>
    <script type="text/javascript">
        (function ($) {
            $(function () {
                $('.user-first-name-wrap').before($('.user-last-name-wrap'));
            });
        })(jQuery);
    </script>
	<?php
}
add_action( 'admin_head-profile.php', 'growp_admin_head_post_profile' );
add_action( 'admin_head-user-edit.php', 'growp_admin_head_post_profile' );


function growp_admin_head_site_option() {
	?>
    <style>
        .acf-field-relationship[data-name='o_search_excludes'] .acf-relationship .list {
            height: 100%;
            max-height: 400px;
        }
    </style>
	<?php
}
add_action( 'admin_head', 'growp_admin_head_site_option' );


/**
 * Admin Columns Pro 5.7系で挙動がおかしいときのコード
 **/
// add_filter( 'posts_groupby', function ($groupby) {
// 	if ( is_admin() ){
// 		$screen = get_current_screen();
// 		if ( $screen->id === "edit-estate" ){
// 			global $wpdb;
// 			$groupby = "{$wpdb->posts}.ID";
// 			return $groupby;
// 		}
// 	}
// 	return $groupby;
// } );

// add_filter( 'posts_where', function ($where) {
// 	if ( is_admin() ){
// 		$screen = get_current_screen();
// 		if ( $screen->id === "edit-estate" ){
// 			$where = str_replace("AND ((()))","",$where);
// 			return $where;
// 		}
// 	}
// 	return $where;
// } );
