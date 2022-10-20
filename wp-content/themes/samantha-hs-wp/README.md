# growp / WordPress 開発用テーマ

WordPressの開発用テーマです。

# 特徴

* 子テーマとして [grow-template](https://github.com/growgroup/grow-template) をインストールし、HTMLコーディング時から継続してSass, JavaSCriptの開発が可能となるように
* [grow-template](https://github.com/growgroup/grow-template) と整合性の取れた簡易的なコンポーネント管理
* Theme wrapper(http://scribu.net/wordpress/theme-wrappers.html) を利用したベーステンプレートの提供
* WordPressコーディングにおいてよく利用するテンプレートタグをクラスでラップしたスタティックなメソッドの提供
* 初期状態で設定しておくべきアクション/フィルターフックの提供

# ファイル構造

**第1階層**|**第2階層**|**第3階層**|**第4階層**|**説明**
-----|-----|-----|-----|-----
.editorconfig| | | |editorconfig 用の設定ファイル
.gitignore| | | |-
.travis.yml| | | |-
404.php| | | |404ページのテンプレートファイル
archive.php| | | |記事一覧用
attachment.php| | | |画像詳細ページ用
author.php| | | |著者アーカイブページ用  / 著者アーカイブをデフォルトでは無効にします
base-author.php| | | |著者アーカイブページ用  / 著者アーカイブをデフォルトでは無効にします
base.php| | | |テーマ内で最初に読み込むテンプレートファイル。ヘッダー、フッターやその他テンプレートを呼び出すように設定
bin/| | | |-
composer.json| | | |composer の設定ファイル
composer.lock| | | |-
front-page.php| | | |トップページのテンプレートファイル
functions.php| | | |テーマのための関数用ファイル。src/ 内のファイルを呼び出す。
home.php| | | |投稿記事トップページのテンプレートファイル
index.php| | | |すべてのテンプレートが該当しない場合に呼び出されるテンプレートファイル
languages/| | | |言語ファイル
login.css| | | |ログイン画面用のcssファイル
overwrite.css| | | |簡易的にcssを調整する際に利用するファイル
overwrite.js| | | |簡易的にJavaScriptを調整する際に利用するファイル
page-example.php| | | |固定ページテンプレートのサンプルファイル。
page-noautop.php| | | |固定ページで自動整形フィルタをオフにするテンプレートファイル
page-sitemap.php| | | |サイトマップ出力用テンプレートファイル
page.php| | | |固定ページ用テンプレートファイル
phpunit.xml.dist| | | |-
search.php| | | |検索結果用テンプレートファイル
single.php| | | |投稿記事ページ用テンプレートファイル
style.css| | | |テーマとして認識するためのファイル。読み込まない。
src/| | | |テーマのカスタマイズ用のphpファイルを格納
   |classes/| | |テーマ内でよく利用する機能をclassとして提供
   |   |class-menu-posts.php| |WordPressのナビゲーションを便利に呼び出すためのclass定義ファイル
   |   |class-post-type.php| |投稿タイプ、タクソノミーをphpから追加するためのclass定義ファイル
   |   |class-sitemap.php| |サイトマップを出力するためのclass定義ファイル
   |   |class-tgm-plugin-activation.php| |-
   |   |class-theme-wrapper.php| |Theme Wrapper 機能を提供するファイル
   |   |class-walker-comment.php| |-
   |   |class-walker-nav.php| |-
   |hooks/| | | 
   |   |comment.php| |コメントの拡張ファイル
   |   |default-plugins.php| |初期状態でインストール、またはインストールを推奨するプラグインの定義
   |   |extras.php| |その他、拡張用フックの定義
   |   |scripts.php| |テーマで呼び出すCSS, JavaScriptの定義
   |   |setup.php| |テーマとして機能させるためのフックを定義
   |   |sidebar.php| |-
   |mock/| | |インストール時点でいくつかのモックデータを登録
   |   |class-create-mock.php| | 
   |   |frontandhome-mock.php| | 
   |   |mock.php| | 
   |   |mw-wp-form-mock.php| | 
   |   |privacy-policy-mock.php| | 
   |   |sitemap-mock.php| | 
   |   |tinymce-advanced-mock.php| | 
   |   |wp-admin-ui-customize-mock.php| | 
   |tags/| | |よく利用する関数群をclassのスタティックメソッドとして提供
  | |nav.php| |ナビゲーションに関する関数群 / GNav
  | |tag.php| |テンプレートタグをラップした関数群 / GTag
  | |template.php| |views/ フォルダ内のコンポーネントを呼び出すための関数群 / GTemplate
  | |url.php| |URLを呼び出す際の関数群 / GUrl
tests/| | | |-
vendor/| | | |composer でインストールしたファイル群
   |autoload.php| | | 
   |composer/| | | 
   |mobiledetect/| | | 
views/| | | |サイト内でよく利用するコンポーネント
 |foundation/| | | 
 |   |head.php| |<head>タグに該当する箇所を管理
 |layout/| | | 
 |   |footer.php| |サイトのフッター
 |   |global-nav.php| |サイトのグローバルナビゲーション
 |   |header.php| |サイトのヘッダー
 |   |sidebar.php| |サイトのサイドバー
 |object/| | | 
 | |components/| | 
 | |   |breadcrumb.php|パンくずの出力 (WordPress SEOを利用)
 | |   |comments.php|コメントの出力
 | |   |gallery.php|ギャラリーの出力
 | |   |mainvisual.php|メインビジュアルの出力
 | |   |offer.php|オファーの出力
 | |   |page-header.php|ページヘッダーの出力
 | |project/| | 
 | | |post-item.php|投稿アイテムの出力
 | | |searchform.php|検索フォームの出力
 
# よく利用するテンプレートタグ

## GUrl
	
### ```<?php GUrl::the_url(); ?>```

home_url() のエイリアス

### ```<?php GUrl::the_asset(); ?>```

get_theme_file_uri() のエイリアス

## GTemplate

### ```<?php GTemplate::get_component("component_name"); ?>```

views/object/components/ 内のファイルを呼び出す

### ```<?php GTemplate::get_layout("component_name"); ?>```

views/layouts/ 内のファイルを呼び出す

	
## Codespacesについて
初回起動後以下のコマンドを実行してください。
必要なプラグインなどをインストールします。

```
sh .devcontainer/start.sh
```
