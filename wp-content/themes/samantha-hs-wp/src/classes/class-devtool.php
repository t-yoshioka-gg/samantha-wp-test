<?php
/*
Plugin Name: GrowGroup_開発者ツール
Plugin URI: https://github.com/growgroup/growp/edit/master/src/classes/class-devtool.php
Description: 開発時のツール
Version: 1.0.0
Author: growgroup
Author URI: https://github.com/growgroup/
*/

class GROWP_Devtool {

	protected static $instance = null;

	/**
	 * Frontend constructor.
	 * 初期化
	 */
	private function __construct() {
		add_action( "admin_bar_menu", [ $this, "admin_bar" ], 100 );
		add_action( "wp_footer", [ $this, 'render_template' ] );
		add_action( "wp_footer", [ $this, "render_script" ] );
		$this->render_devinfo();
		add_action( "admin_bar_menu", function ( $wp_admin_bar ) {
			if ( ! is_admin() && is_page() ) {
				$wp_admin_bar->add_node( [
						'id'    => "growp_update_page",
						'title' => "<svg style='vertical-align: middle;' width=\"24px\" height=\"23px\" viewBox=\"0 0 94 91\"><g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\"><g><rect id=\"Rectangle\" fill=\"#FFFFFF\" x=\"0\" y=\"0\" width=\"94\" height=\"91\" rx=\"8\"></rect><path d=\"M34,23 C32.2492851,23.016762 31.0124687,24.2619372 31,26 L31,68.999989 L62,68.999989 C63.7272872,69.0042742 64.9873241,67.7530043 65,66 L65,23 L34,23 Z M58,63 L37,63 C36.2230911,62.9985419 36.0041962,62.7850405 36,62 L36,29 C36,28.2181034 36.2201192,28 37,28 L58,28 C58.7414684,28 58.9615875,28.2181034 59,29 L59,41 L50,41 C49.3717545,40.9474695 49.1516353,41.1655729 49,42 L49,52 C49.1558315,52.3570375 49.3747264,52.5705389 50,52 L54,52 C54.521062,52.5705389 54.7399569,52.3570375 54,52 L54,47 L59,47 L59,62 C59.0004175,62.6474341 58.9433802,62.7774784 59,63 C58.7420065,62.9625493 58.6068483,63.0091408 58,63 Z\" fill=\"#5F6817\" fill-rule=\"nonzero\"></path></g></g></svg> テンプレートから更新",
						'href'  => add_query_arg( [
								'growp_update_page' => "true",
								'nonce'             => wp_create_nonce( __FILE__ )
						] ),
				] );
		
			}
		
			return "";
		}, 100 );
		
		add_action( "admin_bar_menu", function ( $wp_admin_bar ) {
			if ( ! is_admin() && is_page() ) {
				$wp_admin_bar->add_node( [
						'id'    => "growp_update_page",
						'title' => "<svg style='vertical-align: middle;' width=\"24px\" height=\"23px\" viewBox=\"0 0 94 91\"><g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\"><g><rect id=\"Rectangle\" fill=\"#FFFFFF\" x=\"0\" y=\"0\" width=\"94\" height=\"91\" rx=\"8\"></rect><path d=\"M34,23 C32.2492851,23.016762 31.0124687,24.2619372 31,26 L31,68.999989 L62,68.999989 C63.7272872,69.0042742 64.9873241,67.7530043 65,66 L65,23 L34,23 Z M58,63 L37,63 C36.2230911,62.9985419 36.0041962,62.7850405 36,62 L36,29 C36,28.2181034 36.2201192,28 37,28 L58,28 C58.7414684,28 58.9615875,28.2181034 59,29 L59,41 L50,41 C49.3717545,40.9474695 49.1516353,41.1655729 49,42 L49,52 C49.1558315,52.3570375 49.3747264,52.5705389 50,52 L54,52 C54.521062,52.5705389 54.7399569,52.3570375 54,52 L54,47 L59,47 L59,62 C59.0004175,62.6474341 58.9433802,62.7774784 59,63 C58.7420065,62.9625493 58.6068483,63.0091408 58,63 Z\" fill=\"#5F6817\" fill-rule=\"nonzero\"></path></g></g></svg> テンプレートから更新",
						'href'  => add_query_arg( [
								'growp_update_page' => "true",
								'nonce'             => wp_create_nonce( __FILE__ )
						] ),
				] );
			}
			return "";
		}, 100 );
		add_action( "growp_get_content", [$this, 'update_page'] );
	}
	
	public function update_page( $content ) {
		if (
				is_user_logged_in()
				&& isset( $_GET["growp_update_page"] )
				&& $_GET["growp_update_page"]
				&& $_GET["nonce"]
				&& wp_verify_nonce( $_GET["nonce"], __FILE__ )
		) {
			$post_id = get_the_ID();
			$_post   = get_post( $post_id );
			if ( $_post->post_type === "page" && ! $_post->post_content ) {
				// 改行は取り除いた上で挿入する＜ビジュアルエディタからHTMLエディタに変えたときの変な改行を防ぐため＞
				$insert_content = str_replace( "\n", "", $content );
				wp_update_post( [
						"ID"            => $post_id,
						"post_content"  => $insert_content,
						"page_template" => "template-allhtml.php"
				] );
				$url = remove_query_arg( [
						"growp_update_page",
						"nonce",
				] );
				$url = add_query_arg( [ "growp_updated_page" => true ], $url );
				wp_redirect( $url );
				exit;
			}
	
		}
	} 




	public function render_script() {
		if ( is_admin() ){
			return false;
		}
		?>
		<script>
			;(function ($) {
				class MetaInfo {
					constructor() {
						this.namespace = "metainfo";
						this.open = window.localStorage.getItem("growp_metainfo_open");
						if ($.isEmptyObject(this.open)) {
							this.open = false;
						}
						if ( this.open === "false" ){
							this.open = false;
						}

						this.meta_tables = {
							title: {
								label: "title",
								selector: "title",
								callback: function ($el) {
									return $el.text();
								},
								error_case: (result) => {
									if (result) {
										return true;
									}
									return false;
								}
							},
							meta_desc: {
								label: "meta description",
								selector: "meta[name=description]",
								callback: function ($el) {
									return $el.attr("content")
								},
								error_case: (result) => {
									if (result) {
										return true;
									}
									return false;
								}
							},
							meta_index: {
								label: "meta index",
								selector: "meta[name=robots]",
								callback: function ($el) {
									return $el.attr("content")
								},
								error_case: (result) => {
									if (result && result.search("noindex") !== -1) {
										return false;
									}
									return true;
								}
							},
							ogp: {
								label: "meta ogp",
								selector: "meta[property*='og:'],meta[name*='twitter:']",
								callback: function ($el) {
									let items = [];
									for (let a in $el) {
										if (typeof $el[a].outerHTML === "string") {
											items.push($el[a].outerHTML);
										}
									}
									return $("<pre />", {
										text: items.join("\n")
									});
								},
								error_case: (result) => {
									if (result) {
										return true;
									}
									return false;
								}
							},
							h1: {
								label: "h1タグ",
								selector: "h1",
								callback: function ($el) {
									return $el.text();
								},
								error_case: (result) => {
									if (result) {
										return true;
									}
									return false;
								}
							},
							gtm: {
								label: "Googleタグマネージャー",
								selector: "h1",
								callback: function ($el) {
									let $scripttag = $("script[src*='https://www.googletagmanager.com/gtm.js?id=']");
									if ($scripttag.length && Boolean(window.google_tag_manager)) {
										let matches = $scripttag.attr("src").match(/id=(.*?)$/m);
										if (typeof matches[1] !== "undefined") {
											return "[設定済み] ID : <code>" + matches[1] + "</code>";
										}
									}
									return "[未設定]";
								},
								error_case: (result) => {
									if (result === "[未設定]") {
										return false;
									}
									return true;
								}
							},
							ga: {
								label: "GoogleAnalytics",
								selector: "h1",
								callback: function ($el) {
									let $scripttag = $("script[src*='www.google-analytics.com']");
									if ($scripttag.length && Boolean(window.gaData)) {
										var gaid = [];
										var hits = 0;
										for (var _gaid in window.gaData) {
											gaid.push(_gaid);
											hits = window.gaData[_gaid].hitcount
										}
										if (gaid.length === 1) {
											return `[設定済み] ID : <code>${gaid} </code> ヒット回数 : <code>${hits}</code>`;
										} else {
											return "[x 重複設定]";
										}
									}
									return "[未設定]";
								},
								error_case: (result) => {
									if (result === "[未設定]") {
										return false;
									}
									return true;
								}
							},

						};
						this.tables = this.parse();
						this.render();
						if (this.open) {
							this.$wrapper.slideDown(0);
						}
					}

					generateClass(classname) {
						return this.namespace + "-" + classname;
					}

					parse() {
						var tables = [];
						for (var key in this.meta_tables) {
							let $el = $(this.meta_tables[key].selector);
							for (var _key = 0; _key < $el.length; _key++) {
								if ($($el[_key]).closest("#query-monitor-main").length !== 0) {
									delete $el[_key];
								}
							}
							let val = this.meta_tables[key].callback($el);

							tables.push({
								th: this.meta_tables[key].label,
								td: val,
								error: this.meta_tables[key].error_case(val)
							});
						}
						return tables;
					}

					render() {
						let $table = $("<table />", {
							class: 'growpdev-table',
							style: "border-collapse: collapse; width: 100%;"
						});
						for (let key in this.tables) {
							let $tr = $('<tr />', {
								style: "border: 1px solid #ccc;"
							});
							let $th = $('<th />', {
								text: this.tables[key].th,
								style: "width: 200px; text-align: left; font-size: 13px; font-weight: bold; background: #252525; color: #fff; padding: 10px 16px; letter-spacing: 0px;"
							});
							let $td = $('<td />', {
								html: this.tables[key].td,
								style: "text-align: left; font-size: 13px; font-weight: bold; background: #FFF; color: #000; padding: 10px 16px; font-weight: normal; letter-spacing: 0px;"
							});

							if (!this.tables[key].error) {
								$td.css("background", "#F00")
							}
							$tr.append($th);
							$tr.append($td);
							$table.append($tr);
						}
						let $wrapper = $("<div />", {
							style: "display: none; background: #fff; padding: 16px 32px; position: relative; z-index: 1000; width: 100%; border-bottom: 1px solid #ccc;",
							class: this.generateClass("metainfo")
						});
						let $title = $("<div />", {
							style: "font-size: 18px; font-weight: bold; letter-spacing: 0px; color: #000; margin-bottom: 12px;",
							text: "メタ情報"
						});
						let $displayButton = $("<a />", {
							text: "x 隠す",
							style: "font-size: 14px; text-description: underline; letter-spacing: 0px; color: #000; float: right;"
						});
						$displayButton.on("click", (e) => {
							this.toggle();
						});
						$title.append($displayButton);
						$wrapper.append($title);
						$wrapper.append($table);
						$("body").prepend($wrapper);
						this.$wrapper = $wrapper;
					}

					toggle() {
						this.open = !this.open;
						if (this.open) {
							this.$wrapper.slideDown(0);
						} else {
							this.$wrapper.slideUp(0);
						}
						window.localStorage.setItem("growp_metainfo_open", this.open);
					}
				}

				$(function () {
					let metainfo = new MetaInfo();
					$("#wp-admin-bar-metainfo a").on("click", function (e) {
						e.preventDefault();
						metainfo.toggle();
					});

				})
			})(jQuery);
		</script>
		<?php
	}

	public function render_devinfo() {

		if ( isset($_GET["growp_devtool"]) && $_GET["growp_devtool"] === "true" ){
			add_action( "wp", function () {
				if ( ! is_user_logged_in() ){
					return false;
				}
				$current_user = wp_get_current_user();
				if ( ! $current_user->has_cap("administrator") ){
					return "";
				}

				$post_types = get_post_types( [ 'public' => true ] );
				$taxonomies = get_taxonomies();
				?>
				<!doctype html>
				<html lang="ja">
				<head>
				<meta charset="UTF-8">
				<meta robots="noindex,nofollow">

				<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
				<meta http-equiv="X-UA-Compatible" content="ie=edge">
				<?php
				_wp_admin_bar_init();
				wp_head();
				?>
				<style>
					body {
						padding-bottom: 100px;
					}
					.l-container{
						max-width: 900px;
						margin-left: auto;
						margin-right: auto;
					}
					table {
						width: 100%;
						border-collapse: collapse;
					}
					table th {
						background: #f5f5f5;
						padding-top: 8px;
						padding-bottom: 8px;
					}
					table td code {
						background: #e8e8e8;
						padding: 2px 4px;

					}
					table td {
						border: 1px solid #e8e8e8;
						padding-left: 16px;
					}
					table td a.c-label {
						background: #000;
						color: #fff;
						text-decoration: none;
						display: inline-block;
						padding: 4px 8px;
						font-size: 12px;
						border-radius: 4px;
					}
				</style>
				</head>
				<body>
				<div class="l-section">
					<div class="l-container">
						<h1 class="c-heading is-text-left is-xlg">コンテンツ一覧</h1>
						<?php
						foreach ( $post_types as $post_type ) {
							$query = new WP_Query( [
								'post_type'      => $post_type,
								'posts_per_page' => - 1,
							] );
							if ( $query->have_posts() ) {
								$post_type_object = get_post_type_object( $post_type );
								?>
								<h1 class="c-heading is-text-left is-sm is-icon"><?php echo $post_type_object->label ?></h1>
								<table class="c-table is-dev">
									<thead>
										<tr>
											<th>ID</th>
											<th>ページタイトル</th>
											<th>URL</th>
											<th>アクション</th>
										</tr>
									</thead>
									<tbody>
										<?php
										while ( $query->have_posts() ) {
											$query->the_post();
											?>
											<tr>
												<td><code><?php echo get_the_ID() ?></code></td>
												<td>
													<p>
														<a href="<?php the_permalink() ?>"><?php echo get_the_title() ?></a>
													</p>
													<p><?php ?></p>
												</td>
												<td><code><?php echo get_the_permalink() ?></code></td>
												<td>
													<a class="c-label is-sm" href="<?php echo get_edit_post_link() ?>" target="_blank">
														編集
													</a>
													<a class="c-label is-sm" onclick="return (confirm('本当に削除しますか？') ? true : false);" href="<?php echo get_delete_post_link( get_the_ID() ) ?>">
														削除
													</a>
												</td>
											</tr>
											<?php
										}
										?>
									</tbody>
								</table>
								<?php
							}
						}
						foreach ( $taxonomies as $taxonomy ) {
							$taxonomy_object = get_taxonomy( $taxonomy );

							$terms = get_terms( [
								'taxonomy'   => $taxonomy,
								'hide_empty' => false
							] );
							if ( $terms ) {
								?>
								<h1 class="c-heading is-text-left is-sm is-icon"><?php echo $taxonomy_object->label ?></h1>
								<table class="c-table is-dev">
									<thead>
										<tr>
											<th>ID</th>
											<th>ターム名</th>
											<th>アーカイブURL</th>
											<th>アクション</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ( $terms as $term ) {
											?>
											<tr>
												<td><code><?php echo $term->term_id ?></code></td>
												<td>
													<p>
														<a href="<?php echo get_term_link( $term ) ?>"><?php echo $term->name ?></a>
													</p>
													<p><?php ?></p>
												</td>
												<td><code><?php echo get_term_link( $term ) ?></code></td>
												<td>
													<a class="c-label is-sm" href="<?php echo get_edit_term_link($term->term_id) ?>" target="_blank">
														編集
													</a>
													<a class="c-label is-sm" href="<?php echo admin_url( wp_nonce_url( "edit-tags.php?action=delete&taxonomy=$taxonomy&tag_ID=$term->term_id", 'delete-tag_' . $term->term_id ) ); ?>">
														削除
													</a>
												</td>
											</tr>
											<?php
										}
										?>
									</tbody>
								</table>
								<?php
							} else {

							}
						}
						?>

					</div>
				</div>
				<?php
				wp_footer();
				?>
				</body>
				</html>
				<?php
				exit;
			} );
		}
	}

	public function render_template() {
		?>
		<script>
			/**
			 * リンクチェック
			 */
			;(function ($) {
				$(function () {
					$("#wp-admin-bar-broken_link_check").on("click", function (e) {
						e.preventDefault();
						let $links = $("html body a");
						$(".c-slidebar-button,.c-slidebar-menu,.u-hidden-lg,.u-hidden-sm").css({"cssText": "display: block !important"});

						function check_link(i, $links) {
							let max = $links.length;
							let skip = false;
							if (i > max) {
								return false;
							}
							if ($($links[i]).hasClass("qm-link")) {
								skip = true;
							}
							if ($($links[i]).closest("#wpadminbar").length) {
								skip = true;
							}
							if ($($links[i]).closest(".js-notewrap").length) {
								skip = true;
							}
							let url = $($links[i]).attr("href");
							if (typeof url === "undefined") {
								skip = true;
							}
							let $span = $("<span />", {
								text: url,
								class: 'js-link',
								style: 'border: 1px solid #e8e8e8;background: #fff; letter-spacing: 0px; font-weight: normal; position: absolute; font-size: 11px !important; font-family: sans-serif; color: #000;'
							});
							if (!skip && url.search("#") == 0) {
								$($links[i]).css("outline", "4px solid #ff0");
								$($links[i]).attr("title", "要確認");
								$($links[i]).append($span);
								skip = true;
							}

							if (skip) {
								i++;
								check_link(i, $links);
								return true;
							}

							$.ajax({
								url: url,
								type: 'GET',
								async: true,
								timeout: 10000,
								success: function () {
									$($links[i]).css("outline", "4px solid #44d858");
									$($links[i]).attr("title", "成功");
									$($links[i]).append($span);
									i++;
									check_link(i, $links);
								},
								error: function (msg) {
									console.log($($links[i]).attr("href") + " is error");
									$($links[i]).css("outline", "4px solid #ca3e3e");
									$($links[i]).attr("title", "失敗");
									$($links[i]).append($span);
									i++;
									check_link(i, $links);
								}
							});
						}

						check_link(0, $links);
					});
				})
			})(jQuery);
		</script>
		<?php
	}

	/**
	 * 管理バーに追加
	 *
	 * @param $wp_admin_bar
	 *
	 * @return string
	 */
	public function admin_bar( $wp_admin_bar ) {
		if ( ! is_admin() ) {
			$wp_admin_bar->add_node( [
				'id'    => "growp_dev",
				'title' => "<svg style='vertical-align: middle;' width=\"24px\" height=\"23px\" viewBox=\"0 0 94 91\"><g stroke=\"none\" stroke-width=\"1\" fill=\"none\" fill-rule=\"evenodd\"><g><rect id=\"Rectangle\" fill=\"#FFFFFF\" x=\"0\" y=\"0\" width=\"94\" height=\"91\" rx=\"8\"></rect><path d=\"M34,23 C32.2492851,23.016762 31.0124687,24.2619372 31,26 L31,68.999989 L62,68.999989 C63.7272872,69.0042742 64.9873241,67.7530043 65,66 L65,23 L34,23 Z M58,63 L37,63 C36.2230911,62.9985419 36.0041962,62.7850405 36,62 L36,29 C36,28.2181034 36.2201192,28 37,28 L58,28 C58.7414684,28 58.9615875,28.2181034 59,29 L59,41 L50,41 C49.3717545,40.9474695 49.1516353,41.1655729 49,42 L49,52 C49.1558315,52.3570375 49.3747264,52.5705389 50,52 L54,52 C54.521062,52.5705389 54.7399569,52.3570375 54,52 L54,47 L59,47 L59,62 C59.0004175,62.6474341 58.9433802,62.7774784 59,63 C58.7420065,62.9625493 58.6068483,63.0091408 58,63 Z\" fill=\"#5F6817\" fill-rule=\"nonzero\"></path></g></g></svg> 開発",
			] );
			$wp_admin_bar->add_node( [
				'id'     => "broken_link_check",
				'parent' => "growp_dev",
				'title'  => "簡易リンクチェック",
			] );
			$wp_admin_bar->add_node( [
				'id'     => "devinfo",
				'parent' => "growp_dev",
				'title'  => "全ページ情報",
				'href'   => '/?growp_devtool=true',
			] );
			$wp_admin_bar->add_node( [
				'id'     => "metainfo",
				'parent' => "growp_dev",
				'title'  => "SEO情報",
				'href'   => "#",
			] );
		}

		return "";
	}

	/**
	 * シングルトンインスタンスを取得
	 * @return null
	 */
	public static function get_instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}
}

GROWP_Devtool::get_instance();
