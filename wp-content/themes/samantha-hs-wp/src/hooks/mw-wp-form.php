<?php
/**
 * MW WP Form 関連のフィルタをさまざま用意
 * https://github.com/inc2734/mw-wp-form
 *
 */

/**
 * add_products
 * カスタム投稿タイプ「products」の投稿を選択肢として表示
 *
 * @param array $children
 * @param array $atts
 */
function xxx_change_choices( $children, $atts ) {
	if ( $atts['name'] == 'products' ) {
		$products = get_posts( array(
			'post_type'      => 'product',
			'posts_per_page' => - 1
		) );
		foreach ( $products as $product ) {
			$children[ $product->post_title ] = $product->post_title;
		}
	}

	return $children;
}

//add_filter( 'mwform_choices_mw-wp-form-xxx', 'xxx_change_choices', 10, 2 );


/**
 * バリデーションルールを動的に変更
 *
 * @param $Validation
 * @param $data
 * @param $Data
 *
 * @return mixed
 */
function xxx_validation_rule( $Validation, $data, $Data ) {
	if ( $data['コース1'] ) {
		$Validation->set_rule( '年齢', 'noEmpty' );
	}

	return $Validation;
}

//add_filter( 'mwform_validation_mw-wp-form-xxx', 'xxx_validation_rule', 10, 3 );


/**
 * デフォルトの値をパラメータから取得
 *
 * @param $value
 * @param $name
 *
 * @return mixed
 */
function xxx_mwform_value( $value, $name ) {
	if ( $name === 'recruit_id' && ! empty( $_GET['recruit_id'] ) && ! is_array( $_GET['recruit_id'] ) ) {
		return $_GET['recruit_id'];
	}

	return $value;
}

//add_filter( 'mwform_value_mw-wp-form-xxx', 'xxx_mwform_value', 10, 2 );


/**
 * SMTP設定
 *
 * @param $phpmailer
 */
function change_phpmailer_setting( $phpmailer ) {
	$phpmailer->isSMTP();
	$phpmailer->Host = 'smtp.example.com';
	// $phpmailer->SMTPDebug = 3;
	$phpmailer->Port        = 25;
	$phpmailer->Sender      = 'webmaster_noreply@example.com';
	$phpmailer->From        = 'webmaster_noreply@example.com';
	$phpmailer->Username    = 'webmaster_noreply@example.com';
	$phpmailer->Password    = '';
	$phpmailer->SMTPAuth    = false;
	$phpmailer->SMTPSecure  = false;
	$phpmailer->SMTPAutoTLS = false;
	$phpmailer->SMTPOptions = array(
		'ssl' => array(
			'verify_peer'       => false,
			'verify_peer_name'  => false,
			'allow_self_signed' => true
		)
	);
}

//add_action( 'phpmailer_init', 'change_phpmailer_setting' );


/**
 * 管理者宛メールの変更
 *
 * @param $Mail_raw
 * @param $values
 * @param $Data
 *
 * @return mixed
 */
function xxx_admin_mail( $Mail_raw, $values, $Data ) {
	if ( $Data->get( 'c_type' ) == '製品に関するお問合せ' ) {
		$Mail_raw->to      = 'webmaster_product@example.com';
		$Mail_raw->subject = "【test】製品についてお問い合わせがありました";
	}

	return $Mail_raw;
}

//add_filter( 'mwform_admin_mail_raw_mw-wp-form-xxx', 'xxx_admin_mail', 10, 3 );


function xxx_autoreply_mail( $Mail, $Data ) {
	if ( $Data["c_type"] == '製品に関するお問合せ' ) {
		$Mail->subject = "【xxx】製品についてお問い合わせがありました";
	}

	return $Mail;
}

//add_filter( 'mwform_mail_mw-wp-form-xxx', 'xxx_autoreply_mail', 10, 2 );


/**
 * アップロードディレクトリを変更
 *
 * @param $path
 * @param $Data
 * @param $key
 *
 * @return string
 */
function xxx_mwform_upload_dir( $path, $Data, $key ) {
	//アップロードディレクトリ以下のパスを指定
	return '/recruitfiles';
}

//add_filter( 'mwform_upload_dir_mw-wp-form-xxx', 'xxx_mwform_upload_dir', 10, 3 );


/**
 * カスタムメールタグ
 * @param $value
 * @param $key
 * @param $insert_contact_data_id
 *
 * @return mixed|string
 */
function xxx_custom_mail_tag( $value, $key, $insert_contact_data_id ) {
	if ( $key === 'contact_name' ) {
		$post_id = get_post_meta( $insert_contact_data_id, "お問い合わせ先", true );
		$post    = get_post( $post_id );
		$value   = $post->post_title;
	}

	return $value;
}
//add_filter( 'mwform_custom_mail_tag', 'xxx_custom_mail_tag', 10, 3 );


/**
 * MW WP FORM に日本語の入力チェックのチェックボックスを追加する
 */
if ( class_exists( "MW_WP_Form_Abstract_Validation_Rule" ) ) {

	class JapaneseValidation extends \MW_WP_Form_Abstract_Validation_Rule {
		/**
		 * バリデーションルール名を指定
		 * @var string
		 */
		protected $name = 'japanese';

		/**
		 * バリデーションチェック
		 *
		 * @param string $key name属性
		 * @param array $option
		 *
		 * @return string エラーメッセージ
		 */
		public function rule( $key, array $options = array() ) {
			$value = $this->Data->get( $key );
			if ( ! \MWF_Functions::is_empty( $value ) ) {
				if ( preg_match( "/(й|ц|у|к|е|н|г|ш|щ|з|х|ъ|ф|ы|в|а|п|р|о|л|д|ж|э|я|ч|с|м|и|т|ь|б|ю|П)/", $value, $matches ) ) {
					$defaults = array(
							'message' => "キリル文字は含むことができません。"
					);
					$options  = array_merge( $defaults, $options );

					return $options['message'];
				}
				// １文字以上日本語が含まれているか？
				if ( ! preg_match( "/[一-龠]+|[ぁ-ん]+|[ァ-ヴー]+|[一-龠]+|[ａ-ｚＡ-Ｚ０-９]/u", $value ) ) {

					$defaults = array(
							'message' => "日本語での入力をお願いします"
					);
					$options  = array_merge( $defaults, $options );

					return $options['message'];
				}
			}
		}

		/**
		 * 設定パネルに追加
		 *
		 * @param numeric $key バリデーションルールセットの識別番号
		 * @param array $value バリデーションルールセットの内容
		 */
		public function admin( $key, $value ) {
			?>
			<label><input type="checkbox" <?php checked( $value[ $this->getName() ],
						1 ); ?> name="<?php echo MWF_Config::NAME; ?>[validation][<?php echo $key; ?>][<?php echo esc_attr( $this->getName() ); ?>]" value="1" /><?php esc_html_e( '日本語チェック',
						'mw-wp-form' ); ?></label>
			<?php
		}
	}

	add_filter( "mwform_validation_rules", function ( $validation_rules ) {
		$validation_rules["japanese"] = new JapaneseValidation();

		return $validation_rules;
	} );
}
