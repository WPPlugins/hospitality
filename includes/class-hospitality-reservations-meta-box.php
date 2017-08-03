<?php

/**
 *
 */
class Hospitality_Reservations_Meta_Box extends Hospitality_Meta_Box {


	/**
	 * Hospitality_Reservations_Meta_Box constructor.
	 */
	public function __construct() {
		$this->setPostType( 'reservations' );
		$this->setMetaBoxID(  'reservations_cpt_meta_box' );
		$this->setMetaBoxTitle(  __( 'Reservations Display Options', GUESTABA_HSP_TEXTDOMAIN ) );
		$this->setNonceId( 'reservations_mb_nonce');
		$this->init_tooltips();
	}

	/**
	 * Function remove_meta_boxes
	 *
	 * Removes other metaboxes on the dashboard that are not pertinent to the rooms custom post type.
	 *
	 * @param none
	 * @return void
	 */
	public function remove_meta_boxes () {
		remove_meta_box('revisionsdiv', 'reservations', 'norm');
		remove_meta_box('slugdiv', 'reservations', 'norm');
		remove_meta_box('authordiv', 'reservations', 'norm');
		remove_meta_box('postcustom', 'reservations', 'norm');
		remove_meta_box('postexcerpt', 'reservations', 'norm');
		remove_meta_box('trackbacksdiv', 'reservations', 'norm');
		remove_meta_box('commentsdiv', 'reservations', 'norm');
        remove_meta_box('commentstatusdiv', 'reservations', 'norm');
        remove_meta_box('pageparentdiv', 'reservations', 'norm');
	}

	/**
	 * Function meta_box_render
	 *
	 * This is the render callback function for the rooms CPT metabox.
	 *
	 * @param none
	 * @return void
	 */
	public function meta_box_render( ) {
		global $post ;


		wp_nonce_field( basename( __FILE__ ), $this->getNonceId() );

		$post_ID = $post->ID;

		/**
		 *
		 * Content settings section
		 */
		// echo '<div class="gst_settings_container">';


		$this->section_heading(__('Guest & Reservation Information', GUESTABA_HSP_TEXTDOMAIN), 'gst-mb-guest-information');

        ?>
        <div class="gst-table-container">
            <div class="gst-table">
                <div class="gst-table-row">
                    <div class="gst-table-cell">
                    <?php
                    $this->text_input( __('First Name', GUESTABA_HSP_TEXTDOMAIN),
                        get_post_meta( $post_ID, 'first_name', true),
                        'first_name'
                    );
                    ?>
                    </div>
                    <div class="gst-table-cell">
                    <?php
                    $this->text_input( __('Middle Name', GUESTABA_HSP_TEXTDOMAIN),
                        get_post_meta( $post_ID, 'middle_name', true),
                        'middle_name'
                    );
                    ?>
                    </div>
                    <div class="gst-table-cell">
                        <?php
                        $this->text_input( __('Last Name', GUESTABA_HSP_TEXTDOMAIN),
                            get_post_meta( $post_ID, 'last_name', true),
                            'last_name'
                        );

                        ?>
                    </div>
                </div>
                <div class="gst-table-row">

                    <div class="gst-table-cell">
                    <?php
                    $this->text_input( __('Suffix', GUESTABA_HSP_TEXTDOMAIN),
                        get_post_meta( $post_ID, 'suffix', true),
                        'suffix'
                    );
                    ?>
                    </div>
                </div>
                <div class="gst-table-row">
                    <div class="gst-table-cell">
                    <?php
                    $this->text_input( __('Mobile Phone', GUESTABA_HSP_TEXTDOMAIN),
                        get_post_meta( $post_ID, 'mobile_phone', true),
                        'mobile_phone'
                    );
                    ?>
                    </div>
                    <div class="gst-table-cell">
                    <?php
                    $this->text_input( __('Work Phone', GUESTABA_HSP_TEXTDOMAIN),
                        get_post_meta( $post_ID, 'work_phone', true),
                        'work_phone'
                    );

                    ?>
                    </div>
                    <div class="gst-table-cell">
                        <?php
                        $this->text_input( __('Home Phone', GUESTABA_HSP_TEXTDOMAIN),
                            get_post_meta( $post_ID, 'home_phone', true),
                            'home_phone'
                        );
                        ?>
                    </div>
                </div>
                <div class="gst-table-row">

                    <div class="gst-table-cell">
                        <?php
                        $this->text_input( __('Email', GUESTABA_HSP_TEXTDOMAIN),
                            get_post_meta( $post_ID, 'email', true),
                            'email'
                        );

                        ?>
                    </div>
                </div>
                <div class="gst-table-row">
                    <div class="gst-table-cell">
                    <?php
                    $this->text_input( __('Address 1', GUESTABA_HSP_TEXTDOMAIN),
                        get_post_meta( $post_ID, 'address1', true),
                        'address1'
                    );

                    ?>
                    </div>
                    <div class="gst-table-cell">
                    <?php
                    $this->text_input( __('Address 2', GUESTABA_HSP_TEXTDOMAIN),
                        get_post_meta( $post_ID, 'address2', true),
                        'address2'
                    );
                    ?>
                    </div>
                </div>
                <div class="gst-table-row">
                    <div class="gst-table-cell">
                    <?php
                    $this->text_input( __('City', GUESTABA_HSP_TEXTDOMAIN),
                        get_post_meta( $post_ID, 'city', true),
                        'city'
                    );

                    ?>
                    </div>
                    <div class="gst-table-cell">
                    <?php
                    // TDOD: contraint state input to list. (drop down list)
                    $this->text_input( __('State', GUESTABA_HSP_TEXTDOMAIN),
                        get_post_meta( $post_ID, 'state', true),
                        'state'
                    );
                    ?>
                    </div>
                    <div class="gst-table-cell">
                    <?php
                    $this->text_input( __('Postal Code', GUESTABA_HSP_TEXTDOMAIN),
                        get_post_meta( $post_ID, 'postal_code', true),
                        'postal_code'
                    );
                    ?>
                    </div>
                </div>
                <div class="gst-table-row">
                    <div class="gst-table-cell">
                        <?php
						$this->number_input(  __('Number of adults', GUESTABA_HSP_TEXTDOMAIN),
							get_post_meta( $post_ID, 'no_of_adults', true),
							'no_of_adults',
							1
						);
                        
                        ?>
                    </div>
					<div class="gst-table-cell">
						<?php
						$this->number_input(  __('Number of children', GUESTABA_HSP_TEXTDOMAIN),
							get_post_meta( $post_ID, 'no_of_children', true),
							'no_of_adults',
							0
						);

						?>
					</div>
					<div class="gst-table-cell">
						<?php
						$this->text_input( __('Payment status', GUESTABA_HSP_TEXTDOMAIN),
							get_post_meta( $post_ID, 'payment_status', true),
							'payment_status'
						);

						?>
					</div>

                </div>
                <div class="gst-table-row">
					<div class="gst-table-cell">
						<?php
						$this->number_input(  __('Room amount', GUESTABA_HSP_TEXTDOMAIN),
							get_post_meta( $post_ID, 'room_amount', true),
							'room_amount',
							0.00
						);
						?>
					</div>
					<div class="gst-table-cell">
						<?php
						$this->number_input(  __('Tax', GUESTABA_HSP_TEXTDOMAIN),
							get_post_meta( $post_ID, 'tax_amount', true),
							'tax_amount',
							0.00
						);

						?>
					</div>
					<div class="gst-table-cell">
						<?php
						$this->number_input(  __('Fee', GUESTABA_HSP_TEXTDOMAIN),
							get_post_meta( $post_ID, 'fee_amount', true),
							'fee_amount',
							0.00
						);

						?>
					</div>
                </div>
                <div class="gst-table-row">

                        <div class="gst-table-cell">
                            <?php
                            $this->number_input(  __('Payment Amount', GUESTABA_HSP_TEXTDOMAIN),
                                get_post_meta( $post_ID, 'payment_amount', true),
                                'payment_amount',
                                0.00
                            );

                            ?>
                        </div>
                </div>

                <div class="gst-table-row">
					<div class="gst-table-cell">
						<?php
						// TODO, move to date util class, start time stored as unix timestamp in milliseconds.
						$start_time = intval( get_post_meta( $post_ID, 'start_time', true));
						$tz = get_option('timezone_string');
						date_default_timezone_set( $tz );
						$date_str = date( __('M d, Y h:i A T', GUESTABA_HSP_TEXTDOMAIN), $start_time / 1000 );
						$this->date_input( __('Date of arrival', GUESTABA_HSP_TEXTDOMAIN),
							$date_str,
							'start_time'
						);
						?>
					</div>
                    <div class="gst-table-cell">
                        <?php
                        // duration stored as milliseconds
                        $duration_in_days = intval(get_post_meta( $post_ID, 'duration', true)  / 86400000 ) ;
                        $this->number_input(  __('Duration (number of nights)', GUESTABA_HSP_TEXTDOMAIN),
                            $duration_in_days,
                            'duration',
                            1
                        );

                        ?>
                    </div>
                    <div class="gst-table-cell">
                        <?php
                        $this->text_input( __('Status', GUESTABA_HSP_TEXTDOMAIN),
                            get_post_meta( $post_ID, 'status', true),
                            'status'
                        );
                        ?>
                    </div>
				</div>
            </div>
        </div> <!-- end gst-table-container -->
        <div id="gst-room-location-message"></div>
        <?php
        $this->post_select( __('Room location', GUESTABA_HSP_TEXTDOMAIN ),
            get_post_meta($post_ID, 'room_location_id', true ),
            'room_location_id',
            'room-locations',
            'room-locations-select',
            array('location_id', 'unit_number', 'room_title'),
            'location_id'
        );



	}


	/**
	 * Function post_meta_save
	 *
	 * This is  post meta data save callback function.
	 *
	 * @param integer $post_id the post ID for the submitted meta data.
	 */
	public function post_meta_save( $post_id ) {
		// Checks save status
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ $this->getNonceId()] ) && wp_verify_nonce( $_POST[ $this->getNonceId() ], basename( __FILE__ ) ) ) ? 'true' : 'false';

		// Exits script depending on save status
		if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
			return;
		}
		$this->update_meta_text( $post_id, 'first_name');
		$this->update_meta_text( $post_id, 'last_name');
		$this->update_meta_text( $post_id, 'middle_name');
		$this->update_meta_text( $post_id, 'suffix');
		$this->update_meta_text( $post_id, 'address1');
		$this->update_meta_text( $post_id, 'address2');
		$this->update_meta_text( $post_id, 'city');
		$this->update_meta_text( $post_id, 'state');
		$this->update_meta_text( $post_id, 'postal_code');
		$this->update_meta_text( $post_id, 'home_phone');
		$this->update_meta_text( $post_id, 'work_phone');
		$this->update_meta_text( $post_id, 'mobile_phone');
		$this->update_meta_text( $post_id, 'email');
		$this->update_meta_integer( $post_id, 'number_of_adults');
		$this->update_meta_integer( $post_id, 'number_of_children');
		$this->update_meta_text( $post_id, 'payment_status');
		$this->update_meta_float( $post_id, 'room_amount');
		$this->update_meta_float( $post_id, 'tax_amount');
		$this->update_meta_float( $post_id, 'fee_amount');
		$this->update_meta_float( $post_id, 'payment_amount');
		$this->update_meta_post_reference( $post_id, 'room_location_id', 'room-locations');
		$this->update_meta_start_time( $post_id );
		$this->update_meta_duration( $post_id);


	}


	/*
	 * Set start time for reservation. Convert date of arrival text to time stamp and adjust to check in time.
	 *
	 * TODO: change date input on metabox so that checkin time can be overriden.
	 */
	protected function update_meta_start_time( $post_id ) {

		$options = get_option( GUESTABA_HSP_OPTIONS_NAME);


		$checkin_time = sanitize_text_field( $options['hsp_checkin_time']);
		$tz_offset = get_option('gmt_offset')  * 3600 ;

		if ( isset( $_POST[ 'start_time' ] ) ) {

			$date_str = sanitize_text_field( $_POST[ 'start_time' ] ) . ' ' . $checkin_time  ;
			$tstamp = strtotime( $date_str ) * 1000 ;
			if ( $tstamp != 0 ) {
				$tstamp -=  $tz_offset * 1000 ;

			}
			else {
				$date_str = sanitize_text_field( $_POST[ 'start_time' ] )  ;
				$tstamp = strtotime( $date_str ) * 1000 ;
			}


			update_post_meta( $post_id, 'start_time', $tstamp );
		}
	}

	protected static function update_meta_duration( $post_id ) {

		if ( isset( $_POST[ 'duration' ] ) ) {
			$ndays = intval( $_POST[ 'duration' ] );
			$nms = $ndays * 86400000;
			update_post_meta( $post_id, 'duration', $nms );
		}
	}

	/*
	 * Function init_tooltips
	 *
	 * This function initializes the tooltips for the UI elements of this metabox.
	 *
	 * @param none
	 *
	 * @return void
	 */
	protected function init_tooltips() {

		$tooltips = array(
			'add_button'          => __( 'Click this button to add a new item to this list.', GUESTABA_HSP_TEXTDOMAIN ),
			'edit_image_button'   => __( 'Click this button select or upload a different image. For best results, choose images 600 px wide by 150 px high.', GUESTABA_HSP_TEXTDOMAIN ),
			'delete_image_button' => __( 'Click this button to remove this image from the slider.', GUESTABA_HSP_TEXTDOMAIN ),
			'delete_text_button'  => __( 'Click this button to remove this item from the list.', GUESTABA_HSP_TEXTDOMAIN ),

		);

		$this->set_tooltips( $tooltips );

	}

}
