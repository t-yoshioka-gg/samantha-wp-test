<?php

if ( class_exists( "RS_CSV_Importer" ) ) {
	class CustomCSVImporter extends RS_CSV_Importer {
		/**
		 * Insert post and postmeta using `RSCSV_Import_Post_Helper` class.
		 *
		 * @param array $post
		 * @param array $meta
		 * @param array $terms
		 * @param string $thumbnail The uri or path of thumbnail image.
		 * @param bool $is_update
		 *
		 * @return RSCSV_Import_Post_Helper
		 */
		public function save_post( $post, $meta, $terms, $thumbnail, $is_update ) {

			// Separate the post tags from $post array
			if ( isset( $post['post_tags'] ) && ! empty( $post['post_tags'] ) ) {
				$post_tags = $post['post_tags'];
				unset( $post['post_tags'] );
			}

			// Special handling of attachments
			if ( ! empty( $thumbnail ) && $post['post_type'] == 'attachment' ) {
				$post['media_file'] = $thumbnail;
				$thumbnail          = null;
			}

			// Add or update the post
			if ( $is_update ) {
				$h = RSCSV_Import_Post_Helper::getByID( $post['ID'] );
				$h->update( $post );
			} else {
				$h = RSCSV_Import_Post_Helper::add( $post );
			}

			// Set post tags
			if ( isset( $post_tags ) ) {
				$h->setPostTags( $post_tags );
			}

			// Set meta data
			$h->setMeta( $meta );

			// Set terms
			foreach ( $terms as $key => $value ) {
				$h->setObjectTerms( $key, $value );
			}

			// Add thumbnail
			if ( $thumbnail ) {
				$h->addThumbnail( $thumbnail );
			}

			return $h;
		}

		public function get_post_id_by_name( $name ) {

		}

		// process parse csv ind insert posts
		function process_posts() {
			$h = new RS_CSV_Helper;

			$handle = $h->fopen( $this->file, 'r' );
			if ( $handle == false ) {
				echo '<p><strong>' . __( 'Failed to open file.', 'really-simple-csv-importer' ) . '</strong></p>';
				wp_import_cleanup( $this->id );

				return false;
			}

			$is_first      = true;
			$post_statuses = get_post_stati();

			echo '<ol>';

			while ( ( $data = $h->fgetcsv( $handle ) ) !== false ) {
				if ( $is_first ) {
					$h->parse_columns( $this, $data );
					$is_first = false;
				} else {
					echo '<li>';

					$post      = array();
					$is_update = false;
					$error     = new WP_Error();

					// 投稿タイプはクルーズ決め打ち
					$post['post_type'] = "cruise";
					// スラッグはクルーズIDに
					$post_name = $h->get_data( $this, $data, 'クルーズID' );
					if ( $post_name ) {
						$post['post_name'] = $post_name;
					}
					if ( ! $post['post_name'] ) {
						$error->add( 'post_name_check',
							sprintf( __( 'クルーズIDが入力されていないか、SJISの文字コードになっている可能性があります。',
								'really-simple-csv-importer' ), $post_id, $post_type, $post_exist->post_type ) );
					}

					if ( $post["post_name"] ) {
						$post_exist = get_page_by_path( strtolower( $post_name ), "OBJECT", $post["post_type"] );
						if ( is_null( $post_exist ) ) { // if the post id is not exists

						} else {
							if ( $post_exist->post_type == $post["post_type"] ) {
								$post['ID'] = $post_exist->ID;
								$is_update  = true;
							} else {
								$error->add( 'post_type_check',
									sprintf( __( 'The post type value from your csv file does not match the existing data in your database. post_id: %d, post_type(csv): %s, post_type(db): %s',
										'really-simple-csv-importer' ), $post_id, $post_type, $post_exist->post_type ) );
							}
						}
					}


					$delete = $h->get_data( $this, $data, '削除用フラグ' );
					if ( ( trim( $delete ) === "Y" || $delete === "Ｙ" ) && $is_update ) {
						wp_trash_post( $post['ID'], false );
						continue;
					}
					if ( trim( $delete ) === "Y" || trim( $delete ) === "Ｙ" ) {
						continue;
					}


					// (login or ID) post_author
					$post_author = $h->get_data( $this, $data, 'post_author' );
					if ( $post_author ) {
						if ( is_numeric( $post_author ) ) {
							$user = get_user_by( 'id', $post_author );
						} else {
							$user = get_user_by( 'login', $post_author );
						}
						if ( isset( $user ) && is_object( $user ) ) {
							$post['post_author'] = $user->ID;
							unset( $user );
						}
					}

					// (string) publish date
					$post_date = $h->get_data( $this, $data, '掲載開始日時' );
					if ( $post_date && 0 === strpos( "0000", $post_date ) ) {
						$post['post_date'] = date( "Y-m-d H:i:s", strtotime( $post_date ) );
					}

					// (string) post status
					$post['post_status'] = "publish";

//				$post_status = $h->get_data( $this, $data, 'post_status' );
//				if ( $post_status ) {
//					if ( in_array( $post_status, $post_statuses ) ) {
//						$post['post_status'] = $post_status;
//					}
//				}

					// (string) post title
					$post_title = $h->get_data( $this, $data, 'クルーズ名' );
					if ( $post_title ) {
						$post['post_title'] = $post_title;
					}

					// (string) post content
//				$post_content = $h->get_data( $this, $data, 'post_content' );
					$post['post_content'] = " ";

					// (string) post thumbnail image uri
//					$post_thumbnail = $h->get_data( $this, $data, '一覧_航路地図' );

					$meta = array();
					$tax  = array();

					$settings = gm_cruise::get_settings();
					foreach ( $settings as $setting ) {
						if ( $post["ID"] ) {
							delete_post_meta( $post["ID"], $setting["id"] );
						}
						if ( ! $setting["import"] ) {
							continue;
						}
						$value = $h->get_data( $this, $data, $setting["name"] );
						if ( $setting["name"] === "クルーズID" ) {
							$value = $post_name;
						}
						if ( $setting["name"] === "出発日" || $setting["name"] === "到着日" ) {
							if ( is_numeric( $value ) && strlen( $value ) === 5 ) {
								$value = gm_cruise::generate_serial_to_date( $value );
							}
						}
						if ( $value ) {
							switch ( $setting["generate"] ) {
								case "meta" :
									$meta[ $setting["id"] ] = $value;
									break;
								case "taxonomy" :
									$tax[ $setting["id"] ][] = $value;
									break;
							}
						}
					}


//
//				// add any other data to post meta
//				foreach ( $data as $key => $value ) {
//					if ( $value !== false && isset( $this->column_keys[ $key ] ) ) {
//						// check if meta is custom taxonomy
//						if ( substr( $this->column_keys[ $key ], 0, 4 ) == 'tax_' ) {
//							// (string, comma divided) name of custom taxonomies
//							$customtaxes     = preg_split( "/,+/", $value );
//							$taxname         = substr( $this->column_keys[ $key ], 4 );
//							$tax[ $taxname ] = array();
//							foreach ( $customtaxes as $key => $value ) {
//								$tax[ $taxname ][] = $value;
//							}
//						} else {
//							$meta[ $this->column_keys[ $key ] ] = $value;
//						}
//					}
//				}

					/**
					 * Filter post data.
					 *
					 * @param array $post (required)
					 * @param bool $is_update
					 */
					$post = apply_filters( 'really_simple_csv_importer_save_post', $post, $is_update );
					/**
					 * Filter meta data.
					 *
					 * @param array $meta (required)
					 * @param array $post
					 * @param bool $is_update
					 */
					$meta = apply_filters( 'really_simple_csv_importer_save_meta', $meta, $post, $is_update );
					/**
					 * Filter taxonomy data.
					 *
					 * @param array $tax (required)
					 * @param array $post
					 * @param bool $is_update
					 */
					$tax = apply_filters( 'really_simple_csv_importer_save_tax', $tax, $post, $is_update );
					/**
					 * Filter thumbnail URL or path.
					 *
					 * @param string $post_thumbnail (required)
					 * @param array $post
					 * @param bool $is_update
					 *
					 * @since 1.3
					 *
					 */
					$post_thumbnail = apply_filters( 'really_simple_csv_importer_save_thumbnail', $post_thumbnail, $post, $is_update );

					/**
					 * Option for dry run testing
					 *
					 * @param bool false
					 *
					 * @since 0.5.7
					 *
					 */
					$dry_run = apply_filters( 'really_simple_csv_importer_dry_run', false );

					if ( ! $error->get_error_codes() && $dry_run == false ) {

						/**
						 * Get Alternative Importer Class name.
						 *
						 * @param string Class name to override Importer class. Default to null (do not override).
						 *
						 * @since 0.6
						 *
						 */
						$class = apply_filters( 'really_simple_csv_importer_class', null );

						// save post data
						if ( $class && class_exists( $class, false ) ) {
							$importer = new $class;
							$result   = $importer->save_post( $post, $meta, $tax, $post_thumbnail, $is_update );
						} else {
							$result = $this->save_post( $post, $meta, $tax, $post_thumbnail, $is_update );
						}

						if ( $result->isError() ) {
							$error = $result->getError();
						} else {
							$post_object = $result->getPost();

							if ( is_object( $post_object ) ) {
								/**
								 * Fires adter the post imported.
								 *
								 * @param WP_Post $post_object
								 *
								 * @since 1.0
								 *
								 */
								do_action( 'really_simple_csv_importer_post_saved', $post_object );
							}

							echo esc_html( sprintf( __( 'Processing "%s" done.', 'really-simple-csv-importer' ), $post_title ) );
						}
					}

					// show error messages
					foreach ( $error->get_error_messages() as $message ) {
						echo esc_html( $message ) . '<br>';
					}

					echo '</li>';
				}
			}

			echo '</ol>';

			$h->fclose( $handle );

			wp_import_cleanup( $this->id );

			echo '<h3>' . __( 'All Done.', 'really-simple-csv-importer' ) . '</h3>';
		}

		// dispatcher
		function dispatch() {
			$this->header();

			if ( empty ( $_GET['step'] ) ) {
				$step = 0;
			} else {
				$step = (int) $_GET['step'];
			}

			switch ( $step ) {
				case 0 :
					$this->greet();
					break;
				case 1 :
					check_admin_referer( 'import-upload' );
					set_time_limit( 0 );
					$result = $this->import();
					if ( is_wp_error( $result ) ) {
						echo $result->get_error_message();
					}
					break;
			}

			$this->footer();
		}
	}

	function custom_simple_csv_importer() {
		$rs_csv_importer = new CustomCSVImporter();
		register_importer( 'cruise_csv', 'CSVインポート', "CSVデータのインポート",
			array( $rs_csv_importer, 'dispatch' ) );
	}

	add_action( 'init', 'custom_simple_csv_importer' );

}
