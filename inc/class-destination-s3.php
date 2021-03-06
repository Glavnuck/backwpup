<?php
// Amazon S3 SDK v2.6.1
// http://aws.amazon.com/de/sdkforphp2/
// https://github.com/aws/aws-sdk-php

/**
 * Documentation: http://docs.amazonwebservices.com/aws-sdk-php-2/latest/class-Aws.S3.S3Client.html
 */
class BackWPup_Destination_S3 extends BackWPup_Destinations {


	/**
	 * @param        $s3region
	 * @param string $s3base_url
	 * @return string
	 */
	protected function get_s3_base_url( $s3region, $s3base_url = '' ) {

		if ( ! empty( $s3base_url ) )
			return $s3base_url;

		switch ( $s3region ) {
			case 'us-east-1':
				return 'https://s3.amazonaws.com';
			case 'us-west-1':
				return 'https://s3-us-west-1.amazonaws.com';
			case 'us-west-2':
				return 'https://s3-us-west-2.amazonaws.com';
			case 'eu-west-1':
				return 'https://s3-eu-west-1.amazonaws.com';
			case 'ap-northeast-1':
				return 'https://s3-ap-northeast-1.amazonaws.com';
			case 'ap-southeast-1':
				return 'https://s3-ap-southeast-1.amazonaws.com';
			case 'ap-southeast-2':
				return 'https://s3-ap-southeast-2.amazonaws.com';
			case 'sa-east-1':
				return 'https://s3-sa-east-1.amazonaws.com';
			case 'cn-north-1':
				return 'https:/cn-north-1.amazonaws.com';
			case 'google-storage':
				return 'https://storage.googleapis.com';
			case 'hosteurope':
				return 'https://cs.hosteurope.de';
			case 'dreamhost':
				return 'https://objects.dreamhost.com';
			default:
				return '';
		}

	}

	/**
	 * @return array
	 */
	public function option_defaults() {

		return array( 's3accesskey' => '', 's3secretkey' => '', 's3bucket' => '', 's3region' => 'us-east-1', 's3base_url' => '', 's3ssencrypt' => '', 's3storageclass' => '', 's3dir' => trailingslashit( sanitize_file_name( get_bloginfo( 'name' ) ) ), 's3maxbackups' => 15, 's3syncnodelete' => TRUE, 's3multipart' => TRUE );
	}


	/**
	 * @param $jobid
	 */
	public function edit_tab( $jobid ) {

		?>
		<h3 class="title"><?php _e( 'S3 Service', 'backwpup' ) ?></h3>
		<p></p>
		<table class="form-table">
			<tr>
				<th scope="row"><label for="s3region"><?php _e( 'Select a S3 service', 'backwpup' ) ?></label></th>
				<td>
					<select name="s3region" id="s3region" title="<?php _e( 'Amazon S3 Region', 'backwpup' ); ?>">
						<option value="us-east-1" <?php selected( 'us-east-1', BackWPup_Option::get( $jobid, 's3region' ), TRUE ) ?>><?php _e( 'Amazon S3: US Standard', 'backwpup' ); ?></option>
						<option value="us-west-1" <?php selected( 'us-west-1', BackWPup_Option::get( $jobid, 's3region' ), TRUE ) ?>><?php _e( 'Amazon S3: US West (Northern California)', 'backwpup' ); ?></option>
						<option value="us-west-2" <?php selected( 'us-west-2', BackWPup_Option::get( $jobid, 's3region' ), TRUE ) ?>><?php _e( 'Amazon S3: US West (Oregon)', 'backwpup' ); ?></option>
						<option value="eu-west-1" <?php selected( 'eu-west-1', BackWPup_Option::get( $jobid, 's3region' ), TRUE ) ?>><?php _e( 'Amazon S3: EU (Ireland)', 'backwpup' ); ?></option>
						<option value="ap-northeast-1" <?php selected( 'ap-northeast-1', BackWPup_Option::get( $jobid, 's3region' ), TRUE ) ?>><?php _e( 'Amazon S3: Asia Pacific (Tokyo)', 'backwpup' ); ?></option>
						<option value="ap-southeast-1" <?php selected( 'ap-southeast-1', BackWPup_Option::get( $jobid, 's3region' ), TRUE ) ?>><?php _e( 'Amazon S3: Asia Pacific (Singapore)', 'backwpup' ); ?></option>
						<option value="ap-southeast-2" <?php selected( 'ap-southeast-2', BackWPup_Option::get( $jobid, 's3region' ), TRUE ) ?>><?php _e( 'Amazon S3: Asia Pacific (Sydney)', 'backwpup' ); ?></option>
						<option value="sa-east-1" <?php selected( 'sa-east-1', BackWPup_Option::get( $jobid, 's3region' ), TRUE ) ?>><?php _e( 'Amazon S3: South America (Sao Paulo)', 'backwpup' ); ?></option>
						<option value="cn-north-1" <?php selected( 'cn-north-1', BackWPup_Option::get( $jobid, 's3region' ), TRUE ) ?>><?php _e( 'Amazon S3: China (Beijing)', 'backwpup' ); ?></option>
						<option value="google-storage" <?php selected( 'google-storage', BackWPup_Option::get( $jobid, 's3region' ), TRUE ) ?>><?php _e( 'Google Storage (Interoperable Access)', 'backwpup' ); ?></option>
						<option value="hosteurope" <?php selected( 'hosteurope', BackWPup_Option::get( $jobid, 's3region' ), TRUE ) ?>><?php _e( 'Hosteurope Cloud Storage', 'backwpup' ); ?></option>
                        <option value="dreamhost" <?php selected( 'dreamhost', BackWPup_Option::get( $jobid, 's3region' ), TRUE ) ?>><?php _e( 'Dream Host Cloud Storage', 'backwpup' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="s3base_url"><?php _e( 'Or a S3 Server URL', 'backwpup' ) ?></label></th>
				<td>
					<input id="s3base_url" name="s3base_url" type="text"  value="<?php echo esc_attr( BackWPup_Option::get( $jobid, 's3base_url' ) );?>" class="regular-text" autocomplete="off" />
				</td>
			</tr>
		</table>

		<h3 class="title"><?php _e( 'S3 Access Keys', 'backwpup' ); ?></h3>
		<p></p>
		<table class="form-table">
			<tr>
				<th scope="row"><label for="s3accesskey"><?php _e( 'Access Key', 'backwpup' ); ?></label></th>
				<td>
					<input id="s3accesskey" name="s3accesskey" type="text"
						   value="<?php echo esc_attr( BackWPup_Option::get( $jobid, 's3accesskey' ) );?>" class="regular-text" autocomplete="off" />
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="s3secretkey"><?php _e( 'Secret Key', 'backwpup' ); ?></label></th>
				<td>
					<input id="s3secretkey" name="s3secretkey" type="password"
						   value="<?php echo esc_attr( BackWPup_Encryption::decrypt( BackWPup_Option::get( $jobid, 's3secretkey' ) ) ); ?>" class="regular-text" autocomplete="off" />
				</td>
			</tr>
		</table>

		<h3 class="title"><?php _e( 'S3 Bucket', 'backwpup' ); ?></h3>
		<p></p>
		<table class="form-table">
			<tr>
				<th scope="row"><label for="s3bucketselected"><?php _e( 'Bucket selection', 'backwpup' ); ?></label></th>
				<td>
					<input id="s3bucketselected" name="s3bucketselected" type="hidden" value="<?php echo esc_attr( BackWPup_Option::get( $jobid, 's3bucket' ) ); ?>" />
					<?php if ( BackWPup_Option::get( $jobid, 's3accesskey' ) && BackWPup_Option::get( $jobid, 's3secretkey' ) ) $this->edit_ajax( array(
																																					   's3accesskey'  => BackWPup_Option::get( $jobid, 's3accesskey' ),
																																					   's3secretkey'  => BackWPup_Encryption::decrypt(BackWPup_Option::get( $jobid, 's3secretkey' ) ),
																																					   's3bucketselected'   => BackWPup_Option::get( $jobid, 's3bucket' ),
																																					   's3base_url' 	=> BackWPup_Option::get( $jobid, 's3base_url' ),
																																					   's3region' 	=> BackWPup_Option::get( $jobid, 's3region' )
																																				  ) ); ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="s3newbucket"><?php _e( 'Create a new bucket', 'backwpup' ); ?></label></th>
				<td>
					<input id="s3newbucket" name="s3newbucket" type="text" value="" class="small-text" autocomplete="off" />
				</td>
			</tr>
		</table>

		<h3 class="title"><?php _e( 'S3 Backup settings', 'backwpup' ); ?></h3>
		<p></p>
		<table class="form-table">
			<tr>
				<th scope="row"><label for="ids3dir"><?php _e( 'Folder in bucket', 'backwpup' ); ?></label></th>
				<td>
					<input id="ids3dir" name="s3dir" type="text" value="<?php echo esc_attr( BackWPup_Option::get( $jobid, 's3dir' ) ); ?>" class="regular-text" />
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( 'File deletion', 'backwpup' ); ?></th>
				<td>
					<?php
					if ( BackWPup_Option::get( $jobid, 'backuptype' ) == 'archive' ) {
						?>
						<label for="ids3maxbackups"><input id="ids3maxbackups" name="s3maxbackups" type="text" size="3" value="<?php echo esc_attr( BackWPup_Option::get( $jobid, 's3maxbackups' ) ); ?>" class="small-text help-tip" title="<?php esc_attr_e( 'Oldest files will be deleted first. 0 = no deletion', 'backwpup' ); ?>" />&nbsp;
						<?php  _e( 'Number of files to keep in folder.', 'backwpup' ); ?></label>
						<?php } else { ?>
                        <label for="ids3syncnodelete"><input class="checkbox" value="1"
							   type="checkbox" <?php checked( BackWPup_Option::get( $jobid, 's3syncnodelete' ), TRUE ); ?>
							   name="s3syncnodelete" id="ids3syncnodelete" /> <?php _e( 'Do not delete files while syncing to destination!', 'backwpup' ); ?></label>
						<?php } ?>
				</td>
			</tr>
			<?php if ( BackWPup_Option::get( $jobid, 'backuptype' ) == 'archive' ) { ?>
			<tr>
				<th scope="row"><?php _e( 'Multipart Upload', 'backwpup' ); ?></th>
				<td>
				   <label for="ids3multipart"><input class="checkbox help-tip" value="1" title="<?php esc_attr_e( 'Multipart splits file into multiple chunks while uploading. This is necessary for displaying the upload process and to transfer bigger files. Works without a problem on Amazon. Other services might have issues.', 'backwpup'); ?>"
						type="checkbox" <?php checked( BackWPup_Option::get( $jobid, 's3multipart' ), TRUE ); ?>
						name="s3multipart" id="ids3multipart" /> <?php _e( 'Use multipart upload for uploading a file', 'backwpup' ); ?></label>
				</td>
			</tr>
			<?php }  ?>
		</table>

		<h3 class="title"><?php _e( 'Amazon specific settings', 'backwpup' ); ?></h3>
		<p></p>
		<table class="form-table">
			<tr>
				<th scope="row"><label for="ids3storageclass"><?php _e( 'Amazon: Storage Class', 'backwpup' ); ?></label></th>
				<td>
					<select name="s3storageclass" id="ids3storageclass" title="<?php _e( 'Amazon: Storage Class', 'backwpup' ); ?>">
						<option value="" <?php selected( 'us-east-1', BackWPup_Option::get( $jobid, 's3storageclass' ), TRUE ) ?>><?php _e( 'none', 'backwpup' ); ?></option>
						<option value="REDUCED_REDUNDANCY" <?php selected( 'REDUCED_REDUNDANCY', BackWPup_Option::get( $jobid, 's3storageclass' ), TRUE ) ?>><?php _e( 'Reduced Redundancy', 'backwpup' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="ids3ssencrypt"><?php _e( 'Server side encryption', 'backwpup' ); ?></label></th>
				<td>
					<input class="checkbox" value="AES256"
						   type="checkbox" <?php checked( BackWPup_Option::get( $jobid, 's3ssencrypt' ), 'AES256' ); ?>
						   name="s3ssencrypt" id="ids3ssencrypt" /> <?php _e( 'Save files encrypted (AES256) on server.', 'backwpup' ); ?>
				</td>
			</tr>
		</table>

		<?php
	}


	/**
	 * @param $jobid
	 * @return string
	 */
	public function edit_form_post_save( $jobid ) {

		BackWPup_Option::update( $jobid, 's3accesskey', isset( $_POST[ 's3accesskey' ] ) ? $_POST[ 's3accesskey' ] : '' );
		BackWPup_Option::update( $jobid, 's3secretkey', isset( $_POST[ 's3secretkey' ] ) ? BackWPup_Encryption::encrypt( $_POST[ 's3secretkey' ] ) : '' );
		BackWPup_Option::update( $jobid, 's3base_url', isset( $_POST[ 's3base_url' ] ) ? esc_url_raw( $_POST[ 's3base_url' ] ) : '' );
		BackWPup_Option::update( $jobid, 's3region', isset( $_POST[ 's3region' ] ) ? $_POST[ 's3region' ] : '' );
		BackWPup_Option::update( $jobid, 's3storageclass', isset( $_POST[ 's3storageclass' ] ) ? $_POST[ 's3storageclass' ] : '' );
		BackWPup_Option::update( $jobid, 's3ssencrypt', ( isset( $_POST[ 's3ssencrypt' ] ) && $_POST[ 's3ssencrypt' ] == 'AES256' ) ? 'AES256' : '' );
		BackWPup_Option::update( $jobid, 's3bucket', isset( $_POST[ 's3bucket' ] ) ? $_POST[ 's3bucket' ] : '' );

		$_POST[ 's3dir' ] = trailingslashit( str_replace( '//', '/', str_replace( '\\', '/', trim( stripslashes( $_POST[ 's3dir' ] ) ) ) ) );
		if ( substr( $_POST[ 's3dir' ], 0, 1 ) == '/' )
			$_POST[ 's3dir' ] = substr( $_POST[ 's3dir' ], 1 );
		if ( $_POST[ 's3dir' ] == '/' )
			$_POST[ 's3dir' ] = '';
		BackWPup_Option::update( $jobid, 's3dir', $_POST[ 's3dir' ] );

		BackWPup_Option::update( $jobid, 's3maxbackups', isset( $_POST[ 's3maxbackups' ] ) ? (int)$_POST[ 's3maxbackups' ] : 0 );
		BackWPup_Option::update( $jobid, 's3syncnodelete', ( isset( $_POST[ 's3syncnodelete' ] ) && $_POST[ 's3syncnodelete' ] == 1 ) ? TRUE : FALSE );
		BackWPup_Option::update( $jobid, 's3multipart', ( isset( $_POST[ 's3multipart' ] ) && $_POST[ 's3multipart' ] == 1 ) ? TRUE : FALSE );

		//create new bucket
		if ( !empty( $_POST[ 's3newbucket' ] ) ) {
			try {
				$s3 = Aws\S3\S3Client::factory( array( 	 'key'	=> $_POST[ 's3accesskey' ],
														 'secret'	=> $_POST[ 's3secretkey' ],
														 'region'	=> $_POST[ 's3region' ],
														 'base_url'	=> $this->get_s3_base_url( $_POST[ 's3region' ], $_POST[ 's3base_url' ]),
														 'scheme'	=> 'https',
														 'ssl.certificate_authority' => BackWPup::get_plugin_data( 'cacert' ) ) );
				// set bucket creation region
				if ( $_POST[ 's3region' ] == 'google-storage' || $_POST[ 's3region' ] == 'hosteurope' )
					$region = 'EU';
				else
					$region = $_POST[ 's3region' ];

				if ($s3->isValidBucketName( $_POST[ 's3newbucket' ] ) ) {
					$bucket = $s3->createBucket( array(
													  'Bucket' => $_POST[ 's3newbucket' ] ,
													  'LocationConstraint' => $region
												 ) );
					$s3->waitUntil('bucket_exists', $_POST[ 's3newbucket' ]);
					if ( $bucket->get( 'Location' ) )
						BackWPup_Admin::message( sprintf( __( 'Bucket %1$s created in %2$s.','backwpup'), $_POST[ 's3newbucket' ], $bucket->get( 'Location' ) ) );
					else
						BackWPup_Admin::message( sprintf( __( 'Bucket %s could not be created.','backwpup'), $_POST[ 's3newbucket' ] ), TRUE );
				} else {
					BackWPup_Admin::message( sprintf( __( ' %s is not a valid bucket name.','backwpup'), $_POST[ 's3newbucket' ] ), TRUE );
				}
			}
			catch ( Aws\S3\Exception\S3Exception $e ) {
				BackWPup_Admin::message( $e->getMessage(), TRUE );
			}
			BackWPup_Option::update( $jobid, 's3bucket', $_POST[ 's3newbucket' ] );
		}
	}

	/**
	 * @param $jobdest
	 * @param $backupfile
	 */
	public function file_delete( $jobdest, $backupfile ) {

		$files = get_site_transient( 'backwpup_'. strtolower( $jobdest ), array() );
		list( $jobid, $dest ) = explode( '_', $jobdest );

		if ( BackWPup_Option::get( $jobid, 's3accesskey' ) && BackWPup_Option::get( $jobid, 's3secretkey' ) && BackWPup_Option::get( $jobid, 's3bucket' ) ) {
			try {
				$s3 = Aws\S3\S3Client::factory( array( 	 'key'		=> BackWPup_Option::get( $jobid, 's3accesskey' ),
														 'secret'	=> BackWPup_Encryption::decrypt( BackWPup_Option::get( $jobid, 's3secretkey' ) ),
														 'region'	=> BackWPup_Option::get( $jobid, 's3region' ),
														 'base_url'	=> $this->get_s3_base_url( BackWPup_Option::get( $jobid, 's3region' ), BackWPup_Option::get( $jobid, 's3base_url' ) ),
														 'scheme'	=> 'https',
														 'ssl.certificate_authority' => BackWPup::get_plugin_data( 'cacert' ) ) );

				$s3->deleteObject( array(
										'Bucket' => BackWPup_Option::get( $jobid, 's3bucket' ),
										'Key' => $backupfile
								   ) );
				//update file list
				foreach ( $files as $key => $file ) {
					if ( is_array( $file ) && $file[ 'file' ] == $backupfile )
						unset( $files[ $key ] );
				}
				unset( $s3 );
			}
			catch ( Exception $e ) {
				BackWPup_Admin::message( sprintf( __('S3 Service API: %s','backwpup'), $e->getMessage() ), TRUE );
			}
		}

		set_site_transient( 'backwpup_'. strtolower( $jobdest ), $files, 60 * 60 * 24 * 7 );
	}

	/**
	 * @param $jobid
	 * @param $get_file
	 */
	public function file_download( $jobid, $get_file ) {

		try {
			$s3 = Aws\S3\S3Client::factory( array( 	 'key'		=> BackWPup_Option::get( $jobid, 's3accesskey' ),
													 'secret'	=> BackWPup_Encryption::decrypt( BackWPup_Option::get( $jobid, 's3secretkey' ) ),
													 'region'	=> BackWPup_Option::get( $jobid, 's3region' ),
													 'base_url'	=> $this->get_s3_base_url( BackWPup_Option::get( $jobid, 's3region' ), BackWPup_Option::get( $jobid, 's3base_url' ) ),
													 'scheme'	=> 'https',
													 'ssl.certificate_authority' => BackWPup::get_plugin_data( 'cacert' ) ) );

			$s3file = $s3->getObject( array(
										   'Bucket' => BackWPup_Option::get( $jobid, 's3bucket' ),
										   'Key' => $get_file ) );
		}
		catch ( Exception $e ) {
			die( $e->getMessage() );
		}

		if ( $s3file[ 'ContentLength' ] > 0 && ! empty( $s3file[ 'ContentType' ] ) ) {
			header( "Pragma: public" );
			header( "Expires: 0" );
			header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );;
			header( "Content-Type: application/octet-stream" );
			header( "Content-Disposition: attachment; filename=" . basename( $get_file ) . ";" );
			header( "Content-Transfer-Encoding: binary" );
			header( "Content-Length: " . $s3file[ 'ContentLength' ] );
			@set_time_limit( 300 );
			$body = $s3file->get( 'Body' );
			$body->rewind();
			while ( $filedata = $body->read( 1024 ) ) {
				echo $filedata;
			}
			die();
		}
	}

	/**
	 * @param $jobdest
	 * @return mixed
	 */
	public function file_get_list( $jobdest ) {

		return get_site_transient( 'backwpup_' . strtolower( $jobdest ) );
	}

	/**
	 * @param $job_object
	 * @return bool
	 */
	public function job_run_archive( &$job_object ) {

		$job_object->substeps_todo = 2 + $job_object->backup_filesize;
		if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] )
			$job_object->log( sprintf( __( '%d. Trying to send backup file to S3 Service&#160;&hellip;', 'backwpup' ), $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] ), E_USER_NOTICE );

		try {
			$s3 = Aws\S3\S3Client::factory( array( 	'key'		=> $job_object->job[ 's3accesskey' ],
												  	'secret'	=> BackWPup_Encryption::decrypt( $job_object->job[ 's3secretkey' ] ),
													'region'	=> $job_object->job[ 's3region' ],
													'base_url'	=> $this->get_s3_base_url( $job_object->job[ 's3region' ], $job_object->job[ 's3base_url' ] ),
													'scheme'	=> 'https',
													'ssl.certificate_authority' => BackWPup::get_plugin_data( 'cacert' ) ) );

			if ( $job_object->steps_data[ $job_object->step_working ]['SAVE_STEP_TRY'] != $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] && $job_object->substeps_done < $job_object->backup_filesize ) {
				if ( $s3->doesBucketExist( $job_object->job[ 's3bucket' ] ) ) {
					$bucketregion = $s3->getBucketLocation( array( 'Bucket' => $job_object->job[ 's3bucket' ] ) );
					$job_object->log( sprintf( __( 'Connected to S3 Bucket "%1$s" in %2$s', 'backwpup' ), $job_object->job[ 's3bucket' ], $bucketregion->get( 'Location' ) ), E_USER_NOTICE );
				}
				else {
					$job_object->log( sprintf( __( 'S3 Bucket "%s" does not exist!', 'backwpup' ), $job_object->job[ 's3bucket' ] ), E_USER_ERROR );

					return TRUE;
				}

				if ( $job_object->job[ 's3multipart' ] ) {
					//Check for aboded Multipart Uploads
					$job_object->log( __( 'Checking for not aborted multipart Uploads&#160;&hellip;', 'backwpup' ) );
					$multipart_uploads = $s3->listMultipartUploads( array( 	'Bucket' => $job_object->job[ 's3bucket' ] ) );
					$uploads = $multipart_uploads->get( 'Uploads' );
					if ( ! empty( $uploads ) ) {
						foreach( $uploads as $upload ) {
							if ( empty( $job_object->steps_data[ $job_object->step_working ][ 'UploadId' ] ) || $job_object->steps_data[ $job_object->step_working ][ 'UploadId' ] != $upload[ 'UploadId' ] ) {
								$s3->abortMultipartUpload( array( 'Bucket' => $job_object->job[ 's3bucket' ], 'Key' => $upload[ 'Key' ], 'UploadId' => $upload[ 'UploadId' ] ) );
								$job_object->log( sprintf( __( 'Upload for %s aborted.', 'backwpup' ), $upload[ 'Key' ] ) );
							}
						}
					}
				}

				//transfer file to S3
				$job_object->log( __( 'Starting upload to S3 Service&#160;&hellip;', 'backwpup' ) );
			}


			if ( ! $job_object->job[ 's3multipart' ] ) {
				//Prepare Upload
				$create_args                 	= array();
				$create_args[ 'Bucket' ] 	 	= $job_object->job[ 's3bucket' ];
				$create_args[ 'ACL' ]        	= 'private';
				if ( ! empty( $job_object->job[ 's3ssencrypt' ] ) )
					$create_args[ 'ServerSideEncryption' ] = $job_object->job[ 's3ssencrypt' ]; //AES256
				if ( ! empty( $job_object->job[ 's3storageclass' ] ) ) //REDUCED_REDUNDANCY
					$create_args[ 'StorageClass' ] = $job_object->job[ 's3storageclass' ];
				$create_args[ 'Metadata' ]   	= array( 'BackupTime' => date_i18n( 'Y-m-d H:i:s', $job_object->start_time ) );

				$create_args[ 'Body' ] 	  		= fopen( $job_object->backup_folder . $job_object->backup_file, 'r' );
				$create_args[ 'Key' ] 		 	= $job_object->job[ 's3dir' ] . $job_object->backup_file;
				$create_args[ 'ContentType' ]	= $job_object->get_mime_type( $job_object->backup_folder . $job_object->backup_file );

				try {
					$s3->putObject( $create_args );
				} catch ( Aws\Common\Exception\MultipartUploadException $e ) {
					$job_object->log( E_USER_ERROR, sprintf( __( 'S3 Service API: %s', 'backwpup' ), htmlentities( $e->getMessage() ) ), $e->getFile(), $e->getLine() );

					return FALSE;
				}
			} else {
				//Prepare Upload
				$job_object->steps_data[ $job_object->step_working ][ 'file_handel' ] = fopen( $job_object->backup_folder . $job_object->backup_file, 'rb' );
				fseek( $job_object->steps_data[ $job_object->step_working ][ 'file_handel' ], $job_object->substeps_done );

				try {

					if ( empty ( $job_object->steps_data[ $job_object->step_working ][ 'UploadId' ] ) ) {
						$args = array(	'ACL' 			=> 'private',
										'Bucket' 		=> $job_object->job[ 's3bucket' ],
										'ContentType' 	=> $job_object->get_mime_type( $job_object->backup_folder . $job_object->backup_file ),
										'Key'			=> $job_object->job[ 's3dir' ] . $job_object->backup_file );
						if ( !empty( $job_object->job[ 's3ssencrypt' ] ) )
							$args[ 'ServerSideEncryption' ] = $job_object->job[ 's3ssencrypt' ];
						if ( !empty( $job_object->job[ 's3storageclass' ] ) )
							$args[ 'StorageClass' ] = empty( $job_object->job[ 's3storageclass' ] ) ? 'STANDARD' : 'REDUCED_REDUNDANCY';

						$upload = $s3->createMultipartUpload( $args );

						$job_object->steps_data[ $job_object->step_working ][ 'UploadId' ] = $upload->get( 'UploadId' );
						$job_object->steps_data[ $job_object->step_working ][ 'Parts' ] = array();
						$job_object->steps_data[ $job_object->step_working ][ 'Part' ] = 1;
					}

					while ( ! feof( $job_object->steps_data[ $job_object->step_working ][ 'file_handel' ] ) ) {
						$chunk_upload_start = microtime( TRUE );
						$part_data = fread( $job_object->steps_data[ $job_object->step_working ][ 'file_handel' ], 1048576 * 5 ); //5MB Minimum part size
						$part = $s3->uploadPart( array(	'Bucket'	=> $job_object->job[ 's3bucket' ],
														'UploadId'  => $job_object->steps_data[ $job_object->step_working ][ 'UploadId' ],
														'Key'		=> $job_object->job[ 's3dir' ] . $job_object->backup_file,
														'PartNumber' => $job_object->steps_data[ $job_object->step_working ][ 'Part' ],
														'Body' 		=> $part_data ) );
						$chunk_upload_time = microtime( TRUE ) - $chunk_upload_start;
						$job_object->substeps_done = $job_object->substeps_done + strlen( $part_data );
						$job_object->steps_data[ $job_object->step_working ][ 'Parts' ][] = array( 	'ETag' 		 => $part->get( 'ETag' ),
																								   	'PartNumber' => $job_object->steps_data[ $job_object->step_working ][ 'Part' ] );
						$job_object->steps_data[ $job_object->step_working ][ 'Part' ]++;
						$time_remaining = $job_object->do_restart_time();
						if ( $time_remaining < $chunk_upload_time )
							$job_object->do_restart_time( TRUE );
						$job_object->update_working_data();
					}

					$s3->completeMultipartUpload( array(	'Bucket'	=> $job_object->job[ 's3bucket' ],
															'UploadId'  => $job_object->steps_data[ $job_object->step_working ][ 'UploadId' ],
															'Key'		=> $job_object->job[ 's3dir' ] . $job_object->backup_file,
															'Parts'		=> $job_object->steps_data[ $job_object->step_working ][ 'Parts' ] ) );

				} catch ( Exception $e ) {
					$job_object->log( E_USER_ERROR, sprintf( __( 'S3 Service API: %s', 'backwpup' ), htmlentities( $e->getMessage() ) ), $e->getFile(), $e->getLine() );
					if ( ! empty( $job_object->steps_data[ $job_object->step_working ][ 'uploadId' ] ) )
						$s3->abortMultipartUpload( array(	'Bucket'	=> $job_object->job[ 's3bucket' ],
															'UploadId'  => $job_object->steps_data[ $job_object->step_working ][ 'uploadId' ],
															'Key'		=> $job_object->job[ 's3dir' ] . $job_object->backup_file ) );
					unset( $job_object->steps_data[ $job_object->step_working ][ 'UploadId' ] );
					unset( $job_object->steps_data[ $job_object->step_working ][ 'Parts' ] );
					unset( $job_object->steps_data[ $job_object->step_working ][ 'Part' ] );
					$job_object->substeps_done = 0;
					if ( is_resource( $job_object->steps_data[ $job_object->step_working ][ 'file_handel' ] ) )
						fclose( $job_object->steps_data[ $job_object->step_working ][ 'file_handel' ] );
					return FALSE;
				}
				fclose( $job_object->steps_data[ $job_object->step_working ][ 'file_handel' ] );
			}

			$result = $s3->headObject( array( 	'Bucket' => $job_object->job[ 's3bucket' ],
												'Key' 	 => $job_object->job[ 's3dir' ] . $job_object->backup_file) );

			if ( $result->get( 'ContentLength' ) == filesize( $job_object->backup_folder . $job_object->backup_file ) ) {
				$job_object->substeps_done = 1 + $job_object->backup_filesize;
				$job_object->log( sprintf( __( 'Backup transferred to %s.', 'backwpup' ), $this->get_s3_base_url( $job_object->job[ 's3region' ], $job_object->job[ 's3base_url' ] ). '/' .$job_object->job[ 's3bucket' ] . '/' . $job_object->job[ 's3dir' ] . $job_object->backup_file ), E_USER_NOTICE );
				if ( ! empty( $job_object->job[ 'jobid' ] ) )
					BackWPup_Option::update( $job_object->job[ 'jobid' ], 'lastbackupdownloadurl', network_admin_url( 'admin.php' ) . '?page=backwpupbackups&action=downloads3&file=' . $job_object->job[ 's3dir' ] . $job_object->backup_file . '&jobid=' . $job_object->job[ 'jobid' ] );
			}
			else {
				$job_object->log( sprintf( __( 'Cannot transfer backup to S3! (%1$d) %2$s', 'backwpup' ), $result->get( "status" ), $result->get( "Message" ) ), E_USER_ERROR );
			}
		}
		catch ( Exception $e ) {
			$job_object->log( E_USER_ERROR, sprintf( __( 'S3 Service API: %s', 'backwpup' ), htmlentities( $e->getMessage() ) ), $e->getFile(), $e->getLine() );

			return FALSE;
		}

		try {
			$backupfilelist = array();
			$filecounter    = 0;
			$files          = array();
			$args			= array(
				'Bucket' => $job_object->job[ 's3bucket' ],
				'Prefix' => (string) $job_object->job[ 's3dir' ]
			);
			$objects = $s3->getIterator('ListObjects',  $args );
			if ( is_object( $objects ) ) {
				foreach ( $objects as $object ) {
					$file       = basename( $object[ 'Key' ] );
					$changetime = strtotime( $object[ 'LastModified' ] ) + ( get_option( 'gmt_offset' ) * 3600 );
					if ( $job_object->is_backup_archive( $file ) )
						$backupfilelist[ $changetime ] = $file;
					$files[ $filecounter ][ 'folder' ]      = $this->get_s3_base_url( $job_object->job[ 's3region' ], $job_object->job[ 's3base_url' ] ). '/' .$job_object->job[ 's3bucket' ] . '/' . dirname( $object[ 'Key' ] );
					$files[ $filecounter ][ 'file' ]        = $object[ 'Key' ];
					$files[ $filecounter ][ 'filename' ]    = basename( $object[ 'Key' ] );
					if ( ! empty( $object[ 'StorageClass' ] ) )
						$files[ $filecounter ][ 'info' ]    = sprintf( __('Storage Class: %s', 'backwpup' ), $object[ 'StorageClass' ] );
					$files[ $filecounter ][ 'downloadurl' ] = network_admin_url( 'admin.php' ) . '?page=backwpupbackups&action=downloads3&file=' . $object[ 'Key' ] . '&jobid=' . $job_object->job[ 'jobid' ];
					$files[ $filecounter ][ 'filesize' ]    = $object[ 'Size' ];
					$files[ $filecounter ][ 'time' ]        = $changetime;
					$filecounter ++;
				}
			}
			if ( $job_object->job[ 's3maxbackups' ] > 0 && is_object( $s3 ) ) { //Delete old backups
				if ( count( $backupfilelist ) > $job_object->job[ 's3maxbackups' ] ) {
					ksort( $backupfilelist );
					$numdeltefiles = 0;
					while ( $file = array_shift( $backupfilelist ) ) {
						if ( count( $backupfilelist ) < $job_object->job[ 's3maxbackups' ] )
							break;
						//delete files on S3
						$args = array(
							'Bucket' => $job_object->job[ 's3bucket' ],
							'Key' => $job_object->job[ 's3dir' ] . $file
						);
						if ( $s3->deleteObject( $args ) ) {
							foreach ( $files as $key => $filedata ) {
								if ( $filedata[ 'file' ] == $job_object->job[ 's3dir' ] . $file )
									unset( $files[ $key ] );
							}
							$numdeltefiles ++;
						} else {
							$job_object->log( sprintf( __( 'Cannot delete backup from %s.', 'backwpup' ), $this->get_s3_base_url( $job_object->job[ 's3region' ], $job_object->job[ 's3base_url' ] ). '/' .$job_object->job[ 's3bucket' ] . '/' . $job_object->job[ 's3dir' ] . $file ), E_USER_ERROR );
						}
					}
					if ( $numdeltefiles > 0 )
						$job_object->log( sprintf( _n( 'One file deleted on S3 Bucket.', '%d files deleted on S3 Bucket', $numdeltefiles, 'backwpup' ), $numdeltefiles ), E_USER_NOTICE );
				}
			}
			set_site_transient( 'backwpup_' . $job_object->job[ 'jobid' ] . '_s3', $files, 60 * 60 * 24 * 7 );
		}
		catch ( Exception $e ) {
			$job_object->log( E_USER_ERROR, sprintf( __( 'S3 Service API: %s', 'backwpup' ), htmlentities( $e->getMessage() ) ), $e->getFile(), $e->getLine() );

			return FALSE;
		}
		$job_object->substeps_done = 2 + $job_object->backup_filesize;

		return TRUE;
	}


	/**
	 * @param $job_object
	 * @return bool
	 */
	public function can_run( $job_object ) {

		if ( empty( $job_object->job[ 's3accesskey' ] ) )
			return FALSE;

		if ( empty( $job_object->job[ 's3secretkey' ] ) )
			return FALSE;

		if ( empty( $job_object->job[ 's3bucket' ] ) )
			return FALSE;

		return TRUE;
	}

	/**
	 *
	 */
	public function edit_inline_js() {
		//<script type="text/javascript">
		?>
		function awsgetbucket() {
            var data = {
                action: 'backwpup_dest_s3',
                s3accesskey: $('input[name="s3accesskey"]').val(),
                s3secretkey: $('input[name="s3secretkey"]').val(),
                s3bucketselected: $('input[name="s3bucketselected"]').val(),
                s3base_url: $('input[name="s3base_url"]').val(),
                s3region: $('#s3region').val(),
                _ajax_nonce: $('#backwpupajaxnonce').val()
            };
            $.post(ajaxurl, data, function(response) {
                $('#s3bucketerror').remove();
                $('#s3bucket').remove();
                $('#s3bucketselected').after(response);
            });
        }
		$('input[name="s3accesskey"]').change(function() {awsgetbucket();});
		$('input[name="s3secretkey"]').change(function() {awsgetbucket();});
		$('input[name="s3base_url"]').change(function() {awsgetbucket();});
		$('#s3region').change(function() {awsgetbucket();});
		<?php
	}

	/**
	 * @param string $args
	 */
	public function edit_ajax( $args = '' ) {

		$error = '';

		if ( is_array( $args ) ) {
			$ajax = FALSE;
		}
		else {
			if ( ! current_user_can( 'backwpup_jobs_edit' ) )
				wp_die( -1 );
			check_ajax_referer( 'backwpup_ajax_nonce' );
			$args[ 's3accesskey' ]  	= $_POST[ 's3accesskey' ];
			$args[ 's3secretkey' ]  	= $_POST[ 's3secretkey' ];
			$args[ 's3bucketselected' ]	= $_POST[ 's3bucketselected' ];
			$args[ 's3base_url' ]  	 	= $_POST[ 's3base_url' ];
			$args[ 's3region' ]  	 	= $_POST[ 's3region' ];
			$ajax         				= TRUE;
		}
		echo '<span id="s3bucketerror" style="color:red;">';

		if ( ! empty( $args[ 's3accesskey' ] ) && ! empty( $args[ 's3secretkey' ] ) ) {
			try {
				$s3 = Aws\S3\S3Client::factory( array( 	'key'		=> $args[ 's3accesskey' ],
														'secret'	=> BackWPup_Encryption::decrypt( $args[ 's3secretkey' ] ),
														'region'	=> $args[ 's3region' ],
														'base_url'	=> $this->get_s3_base_url( $args[ 's3region' ], $args[ 's3base_url' ]),
														'scheme'	=> 'https',
														'ssl.certificate_authority' => BackWPup::get_plugin_data( 'cacert' ) ) );

				$buckets = $s3->listBuckets();
			}
			catch ( Exception $e ) {
				$error = $e->getMessage();
			}
		}

		if ( empty( $args[ 's3accesskey' ] ) )
			_e( 'Missing access key!', 'backwpup' );
		elseif ( empty( $args[ 's3secretkey' ] ) )
			_e( 'Missing secret access key!', 'backwpup' );
		elseif ( ! empty( $error ) && $error == 'Access Denied' )
			echo '<input type="text" name="s3bucket" id="s3bucket" value="' . esc_attr( $args[ 's3bucketselected' ] ) . '" >';
		elseif ( ! empty( $error ) )
			echo esc_html( $error );
		elseif ( ! isset( $buckets ) || count( $buckets['Buckets']  ) < 1 )
			_e( 'No bucket found!', 'backwpup' );
		echo '</span>';

		if ( !empty( $buckets['Buckets'] ) ) {
			echo '<select name="s3bucket" id="s3bucket">';
			foreach ( $buckets['Buckets']  as $bucket ) {
				echo "<option " . selected( $args[ 's3bucketselected' ], esc_attr( $bucket['Name'] ), FALSE ) . ">" . esc_attr( $bucket['Name'] ) . "</option>";
			}
			echo '</select>';
		}

		if ( $ajax )
			die();
	}
}
