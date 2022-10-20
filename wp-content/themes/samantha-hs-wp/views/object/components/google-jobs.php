<?php
/**
 * 募集要項ページから本コンポーネントを読み込むことでGoogleJobs用の構造化マークアップを出力します。
 * 募集要項ページは、ACFの「募集要項（Googleお仕事検索）」を設定してください。
 * 以下の ※ 部分(企業名・get_description)を必要に応じて修正ください
 */

$google_job = new GGoogleJob( get_bloginfo( "name" ) ); // ※ 引数には企業名を設定します。
$google_job->markup();

class GGoogleJob {

	private $post_id = false;
	private $company_name = "";

	public function __construct( $company_name, $post_id = false ) {
		if ( ! $post_id ) {
			global $post;
			$this->post_id = $post->ID;
		} else {
			$this->post_id = $post_id;
		}
		$this->company_name = $company_name;
	}

	public function markup() {
		?>
		<script type="application/ld+json">
			{
				"@context": "http://schema.org/",
				"@type": "JobPosting",
				"title": "<?php echo $this->get_title(); ?>",
				"validThrough": "<?php echo $this->get_valid_through(); ?>",
				"datePosted": "<?php echo $this->get_date_posted(); ?>",
				"description": "<?php echo $this->get_description(); ?>",
				"employmentType": [
					"<?php echo $this->get_employment(); ?>"
				],
				"hiringOrganization": {
					"@type": "Organization",
					"name": "<?php echo $this->get_company_name(); ?>"
				},
				"jobLocation": {
					"@type": "Place",
					"address": {
						"@type": "PostalAddress",
						"addressRegion": "<?php echo $this->get_location_region();?>",
						"addressLocality": "<?php echo $this->get_location_locality();?>",
						"streetAddress": "<?php echo $this->get_location_address();?>",
						"addressCountry": "JP"
					}
				},<?php echo $this->get_amount();?>
				"identifier": {
					"@type": "PropertyValue",
					"name": "<?php echo $this->get_company_name(); ?>",
					"value": "<?php echo $this->get_title(); ?>"
				}
			}
		</script>

		<?php
	}

	/**
	 * タイトル
	 * @return string
	 */
	public function get_title() {
		return get_the_title( $this->post_id );
	}


	/**
	 * 有効期限
	 * @return string
	 */
	public function get_valid_through() {
		return date_i18n( "Y-m-d", strtotime( time() + " 1 week" ) );
	}

	/**
	 * 投稿日
	 * @return string
	 */
	public function get_date_posted() {
		return get_the_time( "Y-m-d", get_post( $this->post_id ) );
	}

	/**
	 * 募集内容
	 * @return string
	 */
	public function get_description() {

		// ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※
		// ※求人の募集内容を取得する処理を記述します。
		// $descriptionによしなに要項を入力ください。
		// ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※
		$description =  get_field( "c_requirement_detail", $this->post_id );

		$description = str_replace( array( "<th>" ), "■", $description );
		$description = strip_tags( $description );
		$description = addslashes( $description );
		$description = trim( $description );
		$description = str_replace( array( "\r", "\n" ), '<br>', $description );

		return $description;
	}

	/**
	 * 雇用形態
	 * @return bool|mixed
	 */
	public function get_employment() {
		$employments = array();
		$types       = get_field( "c_job_employment", $this->post_id );
		foreach ( $types as $type => $value ) {
			$employments[] = $value;
		}
		if ( empty( $employments ) ) {
			return "";
		} else {
			return implode( ",", $employments );
		}
	}

	/**
	 * 企業名
	 * @return bool|mixed
	 */
	public function get_company_name() {
		return $this->company_name;
	}

	/**
	 * 職種
	 * @return mixed
	 */
	public function get_job_type() {
		return get_field( "c_job_type" );
	}


	/**
	 * 郵便番号
	 * @return mixed
	 */
	public function get_location_postal() {
		$postal_code_str = "";
		$postal_code     = get_field( "c_job_location_postal", $this->post_id );
		if ( $postal_code ) {
			$postal_code_str = '"postalCode": "' . $postal_code . '",';
		}

		return $postal_code_str;

	}

	/**
	 * 都道府県
	 * @return mixed
	 */
	public function get_location_region() {
		return get_field( "c_job_location_region", $this->post_id );
	}

	/**
	 * 市区町村
	 * @return mixed
	 */
	public function get_location_locality() {
		return get_field( "c_job_location_locality", $this->post_id );
	}

	/**
	 * 町域・地番・建物名
	 * @return mixed
	 */
	public function get_location_address() {
		return get_field( "c_job_location_address", $this->post_id );
	}


	/**
	 * 給与
	 * @return string
	 */
	public function get_amount() {
		$unit_text = get_field( "c_job_amount_unit", $this->post_id );
		if ( empty( $unit_text ) ) {
			return "";
		}
		$min_value = get_field( "c_job_amount_min_price", $this->post_id );
		if ( empty( $min_value ) ) {
			return "";
		}
		$max_value = get_field( "c_job_amount_max_price", $this->post_id );
		if ( empty( $min_value ) ) {
			return "";
		}

		?>"baseSalary": {
		"@type": "MonetaryAmount",
		"currency": "JPY",
		"value": {
		"@type": "QuantitativeValue",
		"value": "<?php echo $min_value; ?>",<?php if ( $min_value && $max_value ) { ?>
			"minValue": "<?php echo $min_value; ?>",
			"maxValue": "<?php echo $max_value; ?>",
		<?php } ?>"unitText": "<?php echo $unit_text; ?>"
		}
		},
		<?php
	}


}


