<?php
/**
 * really simple csv importer プラグイン利用時に
 * "acf_{meta_key}" の列がある場合 acf のフィールドとしてインポート
 * @param $meta
 * @param $post
 * @param $isUpdate
 *
 * @return array
 */
function really_simple_csv_importer_save_meta_acf( $meta, $post, $isUpdate ) {
	global $wpdb;
	$sqlNormal = "SELECT post_name
    FROM $wpdb->posts
    WHERE post_type = 'acf-field' AND post_excerpt = '%s' LIMIT 1";

	$metaResult = [];
	foreach ( $meta as $key => $value ) {
		if ( strpos( $key, "acf_" ) === false ) {
			$metaResult[ $key ] = $value;
			continue;
		}
		$acfKey                = preg_replace( '/^acf_(.*)/', '$1', $key );
		$metaResult[ $acfKey ] = $value;

		$keyStr = $acfKey;
		preg_match( '/^(.*)_[0-9]{1,}_(.*)/', $acfKey, $keyMatches );
		if ( isset( $keyMatches[2] ) ) {
			$keyStr = $keyMatches[2];
		}
		$prepared                    = $wpdb->prepare( $sqlNormal, esc_sql( $keyStr ) );
		$fieldKey                    = $wpdb->get_col( $prepared );
		$metaResult[ "_" . $acfKey ] = $fieldKey[0];
	}

	return $metaResult;
}
add_filter( 'really_simple_csv_importer_save_meta', 'really_simple_csv_importer_save_meta_acf', 10, 3 );

