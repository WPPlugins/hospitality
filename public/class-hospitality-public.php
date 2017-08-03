<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Hospitality
 * @subpackage Hospitality/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Hospitality
 * @subpackage Hospitality/public
 * @author     Wes Kempfer <wkempferjr@tnotw.com>
 */
class Hospitality_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $hospitality    The ID of this plugin.
	 */
	private $hospitality;

	/**
	 * Handle for hospitality public javascript.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $hospitality_public_js_handle.
	 */
	private $hospitality_public_js_handle = 'hospitality-public-js';

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $hospitality       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $hospitality, $version ) {

		$this->hospitality = $hospitality;
		$this->version = $version;

	}



	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hospitality_Public_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hospitality_Public_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		global $post;

		$css_suffix = ( defined( 'SCRIPT_DEBUG' ) ) ? '.min.css' : '.css';


		wp_enqueue_style( $this->hospitality, plugin_dir_url( __FILE__ ) . 'css/hospitality-public' . $css_suffix, array(), $this->version, 'all' );

		// TODO: versions need to be corrected in the wp_enqueue_style/script calls.
		$post_type = get_post_type();
			if ( $post_type == "rooms" || $post->post_name == GUESTABA_ROOM_DETAIL_PAGE_NAME || $post->post_name == GUESTABA_ROOMS_LISTING_PAGE_NAME || $this->has_hsp_shortcodes() ) {
		 	wp_enqueue_style( 'hsp-slick', plugin_dir_url( __FILE__ ) . 'lib/slick/slick.css', array(), $this->version, 'all' );
		 	wp_enqueue_style( 'slider-style', plugin_dir_url( __FILE__ ) . 'css/slider-style.css', array(), $this->version, 'all' );
			// wp_enqueue_style( 'hsp-foundation', plugin_dir_url( __FILE__ ) . 'css/foundation.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'hsp-bootstrap', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'hsp-ng-bootstrap', plugin_dir_url( __FILE__ ) . 'css/ui-bootstrap-csp.css', array(), $this->version, 'all' );

			wp_enqueue_style( 'hsp-ng-datapicker', plugin_dir_url( __FILE__ ) . 'css/ngDatepicker.css', array(), $this->version, 'all' );

			wp_enqueue_style( 'hsp-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), $this->version, 'all' );
		}


	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hospitality_Public_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hospitality_Public_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


		global $post ;

		$post_type = get_post_type();

		$js_suffix = ( defined( 'SCRIPT_DEBUG' ) ) ? '.min.js' : '.js';


		// Don't enqueue the slider code for the rooms listing page, only foundation.
		if ( $post->post_name == GUESTABA_ROOMS_LISTING_PAGE_NAME ) {
			wp_enqueue_script( 'hsp-bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array('jquery'), $this->version, true );
			wp_enqueue_script( 'hsp-angular', plugin_dir_url( __FILE__ ) . 'js/angular.min.js', array( ), $this->version, true );

		}




		// Enqueue foundation and slider for room detail pages
		if ( $post_type == "rooms" || $post->post_name == GUESTABA_ROOM_DETAIL_PAGE_NAME || $this->has_hsp_shortcodes() ) {
			wp_enqueue_script( 'hsp-slick', plugin_dir_url( __FILE__ ) . 'lib/slick/slick.min.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( 'hsp-bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array('jquery'), $this->version, true );
			wp_enqueue_script( 'hsp-angular', plugin_dir_url( __FILE__ ) . 'js/angular.min.js', array( ), $this->version, true );
			wp_enqueue_script( 'hsp-angular-route', plugin_dir_url( __FILE__ ) . 'js/angular-route.min.js', array( ), $this->version, true );
			wp_enqueue_script( 'hsp-moment', plugin_dir_url( __FILE__ ) . 'js/moment-with-locales.min.js', array( ), $this->version, true );
			wp_enqueue_script( 'hsp-ng-datapicker', plugin_dir_url( __FILE__ ) . 'js/ngDatepicker.js', array( ), $this->version, true );
			wp_enqueue_script( 'hsp-ng-animate', plugin_dir_url( __FILE__ ) . 'js/angular-animate.min.js', array( ), $this->version, true );
			wp_enqueue_script( 'hsp-ng-touch', plugin_dir_url( __FILE__ ) . 'js/angular-touch.min.js', array( ), $this->version, true );
			wp_enqueue_script( 'hsp-ng-sanitize', plugin_dir_url( __FILE__ ) . 'js/angular-sanitize.min.js', array( ), $this->version, true );
			wp_enqueue_script( 'hsp-ng-bootstrap', plugin_dir_url( __FILE__ ) . 'js/ui-bootstrap-tpls.js', array( ), $this->version, true );
			wp_enqueue_script( 'hsp-ng-map', plugin_dir_url( __FILE__ ) . 'js/ng-map.min.js', array( ), $this->version, true );


			// Ack: https://github.com/urish/angular-spinner
			wp_enqueue_script( 'hsp-spinjs', plugin_dir_url( __FILE__ ) . 'js/spin.js', array( ), $this->version, true );
			wp_enqueue_script( 'hsp-ng-spinner', plugin_dir_url( __FILE__ ) . 'js/angular-spinner.min.js', array( ), $this->version, true );
			wp_enqueue_script( 'hsp-checklist-model', plugin_dir_url( __FILE__ ) . 'js/checklist-model.js', array('hsp-angular' ), $this->version, true );

			wp_enqueue_script( 'hsp-room-reservation', plugin_dir_url( __FILE__ ) . 'js/room-reservation.js', array( ), $this->version, true );

			$this->queue_google_maps();

			wp_enqueue_script( $this->hospitality_public_js_handle, plugin_dir_url( __FILE__ ) . 'js/hospitality-public' . $js_suffix , array( 'hsp-slick' ), $this->version, true );


		}

	}


	/**
	 * Runs wp_localize_script in order to pass localized strings to javascripts.
	 */
	public function localize_scripts () {

		global $post;
		global $wp_query ;

		/** Assumption: post is either of type 'rooms' in which case the post ID
		 * for the post being view is what we want to pass to the client. If the
		 * post name happens to be that of the room detail page, then get the hsp-rooms-id
		 * parameter for the correct post ID to send to the client.
		*/

		$slider_on = true ;

		$wp_post = get_page_by_path( GUESTABA_ROOM_DETAIL_PAGE_NAME,  OBJECT, 'page' ) ;
		if ( ( $wp_post != null && $wp_post->ID == $post->ID ) || $post->post_name == GUESTABA_ROOM_DETAIL_PAGE_NAME  ) {

			if( isset($wp_query->query_vars[ GUESTABA_ROOM_DETAIL_ID_VAR] ) ) {
				$rooms_post_id = urldecode( $wp_query->query_vars[ GUESTABA_ROOM_DETAIL_ID_VAR ] );
			}
			else if ( isset($wp_query->query_vars[ GUESTABA_ROOM_DETAIL_NAME_VAR] )  ) {
				$rooms_post_name = urldecode( $wp_query->query_vars[ GUESTABA_ROOM_DETAIL_NAME_VAR ] );
				$rooms_post = get_page_by_path( $rooms_post_name,  OBJECT, 'rooms' ) ;
				$rooms_post_id = $rooms_post->ID;
			}
			else {
				// This means details page was called with the hsp-rooms-id query var.
				// That should result in a blank page. No point in going any further.
				// @todo: How should this case be handled? Display error? Log error?
				return ;
			}
		}
		else {
			$rooms_post_id = get_the_ID();
			$slider_on = false;
		}

		$room_slider_IDs = $this->get_slider_shortcode_IDs();

		if ( count( $room_slider_IDs ) > 0 ) {
			$slider_on = true;
		}

		$wp_js_info = array('site_url' => __(site_url()));

		$options = get_option( GUESTABA_HSP_OPTIONS_NAME);

		// Get widget contents
		ob_start();
		dynamic_sidebar( 'room_first_widget_area' );
		$top_content_area = ob_get_contents();
		ob_end_clean();

		ob_start();
		dynamic_sidebar( 'room_second_widget_area' );
		$middle_content_area = ob_get_contents();
		ob_end_clean();

		ob_start();
		dynamic_sidebar( 'room_third_widget_area' );
		$bottom_content_area = ob_get_contents();
		ob_end_clean();

		$countryState = new CountryState();
		$countries = $countryState->countries();

		$statesTable = array();
		foreach ( $countries as $country ) {
			$statesTable[ $country ] = $countryState->getStates( $country );
		}


		$entry_point_slug = GUESTABA_DEFAULT_RESERVATION_SLUG;
		if ( isset( $options['hsp_reservation_entry_point_slug'] )) {
			$entry_point_slug = $options['hsp_reservation_entry_point_slug'] ;
		}

        $enableUserControls = false;
        if ( isset( $options['hsp_enable_user_controls'])) {
            $enableUserControls = boolval($options['hsp_enable_user_controls']);
        }

        

		/*
		 * TODO: collapse localized text for roomReservation into single object (array). See userRegistrationl10n below.
		 */
		wp_localize_script( $this->hospitality_public_js_handle , 'objectl10n', array(
			'wpsiteinfo' => $wp_js_info,
			'postID' => $rooms_post_id,
			'sliderOn' => $slider_on,
			'sliderPostIDs' => $room_slider_IDs,
			'js_slider_config_error' => __('Error retrieving slider JS options.',  GUESTABA_HSP_TEXTDOMAIN ),
			'location_map_error' => __('Error retrieving location map options.',  GUESTABA_HSP_TEXTDOMAIN ),
			'server_error' => __('Server error:', GUESTABA_HSP_TEXTDOMAIN ),
			'location_info' => $this->get_geocoding(),
			'location_marker_title' => $this->get_location_address(),
			'currencySymbol' => $options['hsp_currency_symbol'],
			'partialsURL' => plugins_url('hospitality') . '/partials/',
			'searchAndReserveSlug' => $entry_point_slug,
			'browseAndReserveSlug' => 'room-browse',
			'checkInTime' => $options['hsp_checkin_time'],
			'checkOutTime' => $options['hsp_checkout_time'],
			'amenitiesTitle' => $options['hsp_amenities_title'],
			'searchableAmenities' => $options['hsp_searchable_amenities'],
			'taxIsPercentage' => $options['hsp_tax_is_percentage'],
			'feeIsPercentage' => $options['hsp_fee_is_percentage'],
            'enableUserControls' =>  $enableUserControls,
			'dateOfArrivalLabel' => __('Date of arrival', GUESTABA_HSP_TEXTDOMAIN ),
			'lengthOfStayLabel' => __('Length of stay', GUESTABA_HSP_TEXTDOMAIN ),
			'numberOfOccupantsLabel' => __('No of occupants', GUESTABA_HSP_TEXTDOMAIN ),
			'maximumPriceLabel' => __('Maximum price', GUESTABA_HSP_TEXTDOMAIN ),
			'selectedAmenitiesLabel' =>  __('Requried amenities', GUESTABA_HSP_TEXTDOMAIN ),
			'firstNameLabel' =>  __('First name', GUESTABA_HSP_TEXTDOMAIN ),
			'lastNameLabel' =>  __('Last name', GUESTABA_HSP_TEXTDOMAIN ),
			'address1Label' =>  __('Address 1', GUESTABA_HSP_TEXTDOMAIN ),
			'address2Label' =>  __('Address 2', GUESTABA_HSP_TEXTDOMAIN ),
			'cityLabel' =>  __('City', GUESTABA_HSP_TEXTDOMAIN ),
			'stateLabel' =>  __('State', GUESTABA_HSP_TEXTDOMAIN ),
			'zipLabel' =>  __('Postal code (zip)', GUESTABA_HSP_TEXTDOMAIN ),
			'countryLabel' =>  __('Country', GUESTABA_HSP_TEXTDOMAIN ),
			'phoneLabel' =>  __('Phone number', GUESTABA_HSP_TEXTDOMAIN ),
			'mobileLabel' =>  __('Mobile number', GUESTABA_HSP_TEXTDOMAIN ),
			'emailLabel' =>  __('Email adddress', GUESTABA_HSP_TEXTDOMAIN ),
			'adultsLabel' =>  __('Number of adults', GUESTABA_HSP_TEXTDOMAIN ),
			'childrenLabel' =>  __('Number of children', GUESTABA_HSP_TEXTDOMAIN ),
			'roomSearchOpenLabel' =>  __('Step 1: Find an accomodation that fits your needs.', GUESTABA_HSP_TEXTDOMAIN ),
			'roomListTitle' =>  __('Availble Rooms (based on search criteria and vacancies)', GUESTABA_HSP_TEXTDOMAIN ),
			'lengthOfStayLabel' => __('Number of nights?', GUESTABA_HSP_TEXTDOMAIN),
			'numberOfGuestsLabel' => __('Number of Guests', GUESTABA_HSP_TEXTDOMAIN),
			'maxPriceLabel' => __('Max Price', GUESTABA_HSP_TEXTDOMAIN),
			'detailsLabel' => __('Details', GUESTABA_HSP_TEXTDOMAIN),
			'reserveLabel' => __('Reserve', GUESTABA_HSP_TEXTDOMAIN),
			'detailsTitle' => __('Click here to see more info and make a reservation.', GUESTABA_HSP_TEXTDOMAIN),
			'roomListOpenLabel' =>  __('Step 2: Choose one of the listed accommodations.', GUESTABA_HSP_TEXTDOMAIN ),
			'roomReservationOpenLabel' =>  __('Book It', GUESTABA_HSP_TEXTDOMAIN ),
			'reservationInstructions' =>  __('You are reserving the following accomodation for', GUESTABA_HSP_TEXTDOMAIN ),
			'reservationTimeUnit' =>  __('nights', GUESTABA_HSP_TEXTDOMAIN ),
			'confirmationMessage' =>  __('You reservation is confirmed. Here is your confirmation number: ', GUESTABA_HSP_TEXTDOMAIN ),
            'confirmationHeading' =>  __('Confirmation details', GUESTABA_HSP_TEXTDOMAIN ),
            'checkInTimeLabel' =>  __('Check in', GUESTABA_HSP_TEXTDOMAIN ),
            'checkOutTimeLabel' =>  __('Check out', GUESTABA_HSP_TEXTDOMAIN ),
            'pricePerDayLabel' =>  __('Price per day', GUESTABA_HSP_TEXTDOMAIN ),
			'roomAmountLabel' =>  __('Room rental', GUESTABA_HSP_TEXTDOMAIN ),
			'taxAmountLabel' =>  __('Tax', GUESTABA_HSP_TEXTDOMAIN ),
			'feeAmountLabel' =>  __('Fee', GUESTABA_HSP_TEXTDOMAIN ),
			'amountChargedLabel' =>  __('Amount charged', GUESTABA_HSP_TEXTDOMAIN ),
			'makeAnotherReservationLabel' =>  __('Book another reservation', GUESTABA_HSP_TEXTDOMAIN ),
			'totalLabel' =>  __('Total', GUESTABA_HSP_TEXTDOMAIN ),
			'cardNumberLabel' =>  __('Card Number', GUESTABA_HSP_TEXTDOMAIN ),
			'cardExpirationLabel' =>  __('Expiration (MM/YY)', GUESTABA_HSP_TEXTDOMAIN ),
			'cardExpirationMonthLabel' =>  __('Expiration MM/', GUESTABA_HSP_TEXTDOMAIN ),
			'cardExpirationYearLabel' =>  __('YY', GUESTABA_HSP_TEXTDOMAIN ),
			'cardCVCLabel' =>  __('CVC', GUESTABA_HSP_TEXTDOMAIN ),
			'nameOnCardLabel' =>  __('Name on card', GUESTABA_HSP_TEXTDOMAIN ),
			'paymentInfoHeading' =>  __('Payment Method', GUESTABA_HSP_TEXTDOMAIN ),
			'payPalLabel' => __('PayPal Express', GUESTABA_HSP_TEXTDOMAIN),
			'paymentGatewayPayPalExpress' => Payment_Gateway_Factory::PAYPAL_EXPRESS,
			'offlineLabel' => __('Offline',GUESTABA_HSP_TEXTDOMAIN),
			'paymentGatewayOffline' => Payment_Gateway_Factory::OFFLINE,
			'paymentGatewayOfflineInstructions' => $options['hsp_payment_gateway_offline_instructions'],
			'noRoomsFoundMessage' => __('No accommodations found matching the specified date and/or amenities.', GUESTABA_HSP_TEXTDOMAIN),
			'dateOfArrivalErrorMessage' => __('Date of arrival is in the past.', GUESTABA_HSP_TEXTDOMAIN),
			'topSharedContentArea' => $top_content_area,
			'middleSharedContentArea' => $middle_content_area,
			'bottomSharedContentArea' => $bottom_content_area,
			'currentPriceLabel' => __('Price today:',GUESTABA_HSP_TEXTDOMAIN),
			'seeMoreInfoLabel' => __('Rates, location, and guest information.',GUESTABA_HSP_TEXTDOMAIN),
			'locationsLabel' => __('Room Location', GUESTABA_HSP_TEXTDOMAIN),
			'roomDescriptionLabel' => __('Room Description', GUESTABA_HSP_TEXTDOMAIN),
			'dailyPriceLabel' => __('Current Daily Price', GUESTABA_HSP_TEXTDOMAIN),
			'filterLabel' => __('Filter', GUESTABA_HSP_TEXTDOMAIN),
			'loginFormNonce' => wp_create_nonce( 'ajax-login-nonce' ),
			'countries' => $countries,
			'statesTable' => $statesTable,
            'dateFormat' => __('MMMM DD, YYYY', GUESTABA_HSP_TEXTDOMAIN),
			'userRegistrationl10n' => array(
				'registrationFailedMessage' => __('Registration Failed', GUESTABA_HSP_TEXTDOMAIN),
				'firstNameLabel' => __('First Name', GUESTABA_HSP_TEXTDOMAIN),
				'lastNameLabel' => __('Last Name', GUESTABA_HSP_TEXTDOMAIN),
				'address1Label' => __('Street Address 1', GUESTABA_HSP_TEXTDOMAIN),
				'address2Label' => __('Street Address 2', GUESTABA_HSP_TEXTDOMAIN),
				'cityLabel' => __('City', GUESTABA_HSP_TEXTDOMAIN),
				'stateLabel' => __('State', GUESTABA_HSP_TEXTDOMAIN),
				'emailLabel' => __('Email Address', GUESTABA_HSP_TEXTDOMAIN),
				'postalCodeLabel' => __('Postal Code', GUESTABA_HSP_TEXTDOMAIN),
				'countryLabel' => __('Country', GUESTABA_HSP_TEXTDOMAIN),
				'phoneLabel' => __('Phone', GUESTABA_HSP_TEXTDOMAIN),
				'mobileLabel' => __('Mobile Phone', GUESTABA_HSP_TEXTDOMAIN),
				'registrationFormTitle' => __('Registration', GUESTABA_HSP_TEXTDOMAIN ),
				'userRegistrationInstructions' => __('Please fill out and submit this registration form', GUESTABA_HSP_TEXTDOMAIN),
				'userRegistrationSubmitLabel' => __('Sign Up', GUESTABA_HSP_TEXTDOMAIN),
				'loginFormTitle' => __('Login', GUESTABA_HSP_TEXTDOMAIN ),
				'loginInstructions' => __('Enter your user name and password. Then press the login button', GUESTABA_HSP_TEXTDOMAIN),
				'loginSubmitLabel' => __('Log In', GUESTABA_HSP_TEXTDOMAIN),
				'loginCancelLabel' => __('Cancel', GUESTABA_HSP_TEXTDOMAIN),
				'userNameLabel' => __('User Name or Email', GUESTABA_HSP_TEXTDOMAIN),
				'passwordLabel' => __('password', GUESTABA_HSP_TEXTDOMAIN),
				'loginLinkContent' => '<i class="fa fa-user"></i>',
				'logoutLinkContent' => '<i class="fa fa-power-off"></i>',
				'loginLinkTitle' => __('Log in', GUESTABA_HSP_TEXTDOMAIN),
				'logoutLinkTitle' => __('Log out', GUESTABA_HSP_TEXTDOMAIN),
				'loginFailedMessage' => __('User name or password were incorrect.', GUESTABA_HSP_TEXTDOMAIN),
				'logoutFailedMessage' => __('Log out failed.', GUESTABA_HSP_TEXTDOMAIN),
				'postRegistrationMessage' => __('You are registered. You may continue making your reservation from this page. Please check your email to confirm your registration and set your password.', GUESTABA_HSP_TEXTDOMAIN),
				'registrationMessage' => $options['hsp_registration_message'],
				'registrationLinkContent' => __('Register', GUESTABA_HSP_TEXTDOMAIN)

			)

		));
	}


	/**
	 * Register plugin widget areas.
	 *
	 * @since 1.0.0
	 */
	public function register_widget_areas () {

		register_sidebar( array(
			'name' => 'Room First Widget Area',
			'id' => 'room_first_widget_area',
			'before_widget' => '<div class="hsp_widget">',
			'after_widget' => '</div>',
			'before_title' => '<h2 class="hsp_widget_title">',
			'after_title' => '</h2>',
		) );

		register_sidebar( array(
	        'name' => 'Room Second Widget Area',
	        'id' => 'room_second_widget_area',
	        'before_widget' => '<div class="hsp_widget">',
	        'after_widget' => '</div>',
	        'before_title' => '<h2 class="hsp_widget_title">',
	        'after_title' => '</h2>',
	    ) );


	    register_sidebar( array(
	        'name' => 'Room Third Widget Area',
	        'id' => 'room_third_widget_area',
	        'before_widget' => '<div class="hsp_widget">',
	        'after_widget' => '</div>',
	        'before_title' => '<h2 class="hsp_widget_title">',
	        'after_title' => '</h2>',
	    ) );


	}

	private function has_hsp_shortcodes() {

		global $post;

		$post_type = get_post_type();

		$has_shortcodes = false;
		if ( $post_type == "post"|| $post_type == "page" ) {

			foreach( Hospitality_Shortcodes::get_shortcodes() as $shortcode ) {
				if ( has_shortcode( $post->post_content, $shortcode ) ) {
					$has_shortcodes = true;
					break;
				}
			}
		}
		return $has_shortcodes;
	}

	private function get_slider_shortcode_IDs() {

		global $post;
		$pattern = get_shortcode_regex();

		preg_match_all('/'.$pattern.'/s', $post->post_content, $matches );

		$ids = array();
		foreach ( $matches[0] as $match ) {
			if ( preg_match('/room_images/', $match )) {
				if ( preg_match( '/id=\"?[0-9]+\"?/',$match, $id_attr_match ) ) {
					if ( preg_match('/[0-9]+/', $id_attr_match[0], $id_match ) ) {
						$ids[] = $id_match[0];
					}
				}
			}
		}

		return $ids;
	}
	/*
	 * Support function for location_map, queues google api js.
	 *
	 * @since 1.0.4
	 */

	private function queue_google_maps() {

		$options = get_option( GUESTABA_HSP_OPTIONS_NAME );

        $api_key = '';

        if ( isset($options['hsp_google_maps_api_key'] )) {
            $api_key = $options['hsp_google_maps_api_key'];
        }

		$google_map_url = '//maps.googleapis.com/maps/api/js?v=3&key=' . $api_key ;
		wp_enqueue_script(
			'google-maps',
			$google_map_url,
			array(),
			'1.0',
			true
		);

	}


	/**
	 *
	 * Function: get_geocoding
	 *
	 * @return array returns geo coding json string for address in plugin settings.
	 */
	private static function get_geocoding() {

		$option = get_option( GUESTABA_HSP_GEO_OPTIONS_NAME );
		$geocode_json = $option['geocode'] ;
		if ( $geocode_json == null ) {
			$geocoding = array(
				'lat' => GUESTABA_DEFAULT_LAT,
				'lng' => GUESTABA_DEFAULT_LNG
			);

		} else {
			$geocode_array = json_decode( $geocode_json );
			$geocoding = $geocode_array->results[0]->geometry->location ;
		}

			return $geocoding;

	}

	/**
	 *
	 * Function: get_location_address
	 *
	 * @return the location address in the form "Street, City, State, Zip"
	 */
	private static function get_location_address() {

		$option = get_option( GUESTABA_HSP_OPTIONS_NAME );
		$addressString = $option['hsp_street_address_1'] . ', ' . $option['hsp_street_address_2'] . ', ' .  $option['hsp_city'] . ', ' .  $option['hsp_state'] . ', ' .  $option['hsp_postal_code'];
		return $addressString ;

	}


}
