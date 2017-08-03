<?php


/**
 * This class receives ajax requests for the plugin. 
 * 
 * @since      1.0.0
 * @package    Hospitality
 * @subpackage Hospitality/includes
 * @author     Wes Kempfer <wkempferjr@tnotw.com>
 */

class Hospitality_Public_Ajax_Controller {
	
	/*
	 * Function: execute_request
	 * 
	 * This function is registered as the ajax responder for the
	 * plugin in Wordpress. It calls subordinate functions in order
	 * to satisfy the request. The return string from the subordinate
	 * function is output as a client response directly in this function.
	 * 
	 * If an exception is caught by this function, data related to the
	 * exception are formated and sent as an error response to the
	 * client. 
	 * 
	 * @param none directly. Reads $_REQUEST for 'fn' (function) parameter. 
	 * 
	 * Request currently processed: 
	 * get_slider_config, get_amenity_set_list, get_pricing_model. 
	 * See corresponding functions in this class. 
	 * 
	 */
	
	public static function execute_request() {
		
		try {
			switch($_REQUEST['fn']){
				case 'get_slider_config':
					// If  not set, consider it an invalid request.
					if ( !isset( $_REQUEST['documentURL'] ) && !isset( $_REQUEST['postID'] ) ) {
						throw new Exception(__('Invalid rooms slider request.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					$output = self::get_slider_config();
					if ( $output === false ) {
						throw new Exception(__('Could not get slider config for post.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;

				case 'get_geocoding' :
					// This case is a placeholder for now. Using wp_localize_script in class-hospitality-public
					// to pass this information.
					// If  not set, consider it an invalid request.
					if ( !isset( $_REQUEST['documentURL'] )  ) {
						throw new Exception(__('Invalid rooms slider request.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					$output = self::get_geocoding();
					if ( $output === false ) {
						throw new Exception(__('Could not geocoding for address.', GUESTABA_HSP_TEXTDOMAIN ) );
					}

					break;

				case 'get_amenity_set_list':
					// If  not set, consider it an invalid request.
					if ( !isset( $_REQUEST['postID'] ) ) {
						throw new Exception(__('Invalid get amenity set list request.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					$postID = $_REQUEST['postID'];
					$output = self::get_amenity_set_list( $postID );
					if ( $output === false ) {
						throw new Exception(__('Could not get amenity set list with specified post ID.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;
				case 'get_locations':
					$output = self::get_locations();
					if ( $output === false ) {
						throw new Exception(__('Could not get locations.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;
				case 'search_rooms':
					// If  not set, consider it an invalid request.
					if ( !isset( $_REQUEST['searchCriteria'] ) ) {
						throw new Exception(_e('Invalid search rooms request.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					$searchCriteria = $_REQUEST['searchCriteria'];
					$output = self::search_rooms( $searchCriteria );
					if ( $output === false ) {
						throw new Exception(__('Error searching for rooms.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;

				case 'reserve_room':
					// If  not set, consider it an invalid request.
					if ( !isset( $_REQUEST['reservationInfo'] ) ) {
						throw new Exception(__('Invalid room reservation request.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					$reservationInfo = $_REQUEST['reservationInfo'];
					$output = self::reserve_room( $reservationInfo );
					if ( $output === false ) {
						throw new Exception(__('Error reserving room.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;


				case 'get_available_rooms':
					// If  not set, consider it an invalid request.
					if ( !isset( $_REQUEST['searchCriteria'] ) ) {
						throw new Exception(__('Invalid search rooms request.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					$searchCriteria = $_REQUEST['searchCriteria'];
					$output = self::get_available_rooms( $searchCriteria );
					if ( $output === false ) {
						throw new Exception(__('Error searching for rooms.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;
				
				case 'get_all_rooms':
					$output = self::get_all_rooms();
					break;

				case 'gen_demo_data':
					$output = self::gen_demo_data();
					break;

				case 'get_room_location_availability':
					// If  not set, consider it an invalid request.
					if ( !isset( $_REQUEST['searchCriteria'] ) ) {
						throw new Exception(__('Invalid search rooms request.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					$searchCriteria = $_REQUEST['searchCriteria'];
					$output = self::get_room_location_availability( $searchCriteria );
					if ( $output === false ) {
						throw new Exception(__('Error checking room availability.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;

				case 'get_room_locations':
					// If  not set, consider it an invalid request.
					if ( !isset( $_REQUEST['postID'] ) ) {
						throw new Exception(_e('Invalid get room locations request.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					$postID = $_REQUEST['postID'];
					$output = self::get_room_locations( $postID );
					if ( $output === false ) {
						throw new Exception(__('Could not get room locations with specified post ID.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;
				case 'add_room_locations':
					if ( !self::validate_room_location_request() ) {
						throw new Exception(__('Invalid add room locations request.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					$output = self::add_room_location();
					if ( $output === false ) {
						throw new Exception(__('Could not get amenity set list with specified post ID.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;
				case 'modify_room_location':
					// If  not set, consider it an invalid request.
					if ( !isset( $_REQUEST['postID'] ) ) {
						throw new Exception(__('Invalid modify room location request.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					$postID = $_REQUEST['postID'];
					$output = self::modify_room_location( $postID );
					if ( $output === false ) {
						throw new Exception(__('Could not modify room location with specified post ID.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;
				case 'delete_room_location':
					// If  not set, consider it an invalid request.
					if ( !isset( $_REQUEST['postID'] ) ) {
						throw new Exception(__('Invalid delete room location request.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					$postID = $_REQUEST['postID'];
					$output = self::delete_room_location( $postID );
					if ( $output === false ) {
						throw new Exception(__('Could not delete room location with specified post ID.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;
				case 'validate_room_location':
					// If  not set, consider it an invalid request.
					if ( !isset( $_REQUEST['locationID'] ) ) {
						throw new Exception(__('Invalid modify room location request.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
                    
					$output = self::validate_room_location();
					if ( $output === false ) {
						throw new Exception(__('Could not validate room location.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;
				case 'get_pricing_model':
					// If  not set, consider it an invalid request.
					if ( !isset( $_REQUEST['postID']) || $_REQUEST['postID'] == 'undefined' ) {
						throw new Exception(__('Invalid get price model request.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					$postID = $_REQUEST['postID'];
					$output = self::get_pricing_model( $postID );
					if ( $output === false || empty( $output) ) {
						throw new Exception(__('Could not get price model with specified post ID.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;
				case 'get_post_edit_options':
					$output = self::get_post_edit_options();
					if ( $output === false || empty( $output) ) {
						throw new Exception(__('Could not get post edit options.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;

				case 'dismiss_upgrade_message':
					$output = self::dismiss_upgrade_message();
					if ( $output === false || empty( $output) ) {
						throw new Exception(__('Could not get post edit options.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;

				case 'dismiss_setup_message':
					$output = self::dismiss_setup_message();
					if ( $output === false || empty( $output) ) {
						throw new Exception(__('Could not get post edit options.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;

				case 'get_user':
					
					$output = Hospitality_User_Meta_Manager::get_user();
					if ( $output === false || empty( $output) ) {
						throw new Exception(__('Could not retrieve user info.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;
				
				case 'register_user':

					$output = self::register_user();
					if ( $output === false || empty( $output) ) {
						throw new Exception(__('Could not register user.', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;


				case 'logout':

					$output = self::logout();
					if ( $output === false || empty( $output) ) {
						throw new Exception(__('Could not logout', GUESTABA_HSP_TEXTDOMAIN ) );
					}
					break;

				default:
					$output = __('Unknown ajax request sent from client.', GUESTABA_HSP_TEXTDOMAIN );
					break;
	
			}
		} 
		catch ( Exception $e ) {
			$errorData = array(
				'errorData' => 'true',
				'errorMessage' => $e->getMessage(),
				'errorTrace' => $e->getTraceAsString()
			);
			$output = $errorData;
		}

		// Convert $output to JSON and echo it to the browser 
	
		$output=json_encode($output);
		if(is_array($output)){
			print_r($output);	
 		}
		else {
			echo  $output ;
	     }
		die;
	}

	private static function logout() {
		if ( is_user_logged_in() ) {
			wp_logout();
		}
		return array(
			'loggedIn' => 'false',
			'message' => __('User is logged out.', GUESTABA_HSP_TEXTDOMAIN )
		);
	}

	public function ajax_login(){

		// First check the nonce, if it fails the function will break
		check_ajax_referer( 'ajax-login-nonce', 'security' );

		// Nonce is checked, get the POST data and sign user on
		$info = array();
		$info['user_login'] = $_GET['username'];
		$info['user_password'] = $_GET['password'];
		$info['remember'] = true;

		$user_signon = wp_signon( $info, false );
		if ( is_wp_error($user_signon) ){
			echo json_encode(array('loggedIn'=>false, 'message'=>__('Wrong username or password.')));
		} else {
			echo json_encode(array('loggedIn'=>true, 'message'=>__('Login successful, redirecting...')));
		}

		die();
	}

	/**
	 *
	 * Function: get_post_edit_options
	 *
	 * @return array returns options that are required for javascript configuration on client-side input elements.
	 */
	private static function get_post_edit_options() {

		$option = get_option( GUESTABA_HSP_OPTIONS_NAME );
		$post_edit_options = array();
		$post_edit_options['room_excerpt_max_char_count'] = $option['hsp_room_excerpt_len'];
		return $post_edit_options;
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

		$geocode_array = json_decode( $geocode_json );

		return $geocode_array->results[0]->geometry->location ;

	}

	/**
	 *
	 * Function: get_geocoding
	 *
	 * @return array returns geo coding json string for address in plugin settings.
	 */
	private static function dismiss_upgrade_message() {

		$option = get_option( GUESTABA_HSP_MESSAGE_OPTIONS_NAME );
		$option['upgrade_message_displayed'] = true ;

		if ( ! update_option( GUESTABA_HSP_MESSAGE_OPTIONS_NAME, $option )) {
			error_log('Error updating message option,' . __FILE__ . ':' . __LINE__ );
			return false;
		}

		return true;

	}

	private static function dismiss_setup_message() {

		$option = get_option( GUESTABA_HSP_MESSAGE_OPTIONS_NAME );
		$option['setup_message_displayed'] = true ;

		if ( ! update_option( GUESTABA_HSP_MESSAGE_OPTIONS_NAME, $option )) {
			error_log('Error updating setup message option,' . __FILE__ . ':' . __LINE__ );
			return false;
		}

		return true;

	}



	private static function get_slider_config() {

		$slider_config_array = array();
		if ( isset($_REQUEST['sliderPostIDs']) && count( $_REQUEST['sliderPostIDs']) > 0 ) {
			foreach ( $_REQUEST['sliderPostIDs'] as $postID ) {
				$slider_config_array[] = self::get_slider_config_meta( $postID );
			}

		}
		else {
			$slider_config_array[] = self::get_slider_config_meta( $_REQUEST['postID'] );
		}

		return $slider_config_array;

	}

	/*
	 * Function: get_slider_config_meta()
	 * 
	 * Retrieved the slider configuration settings: navigaition on/off, 
	 * slider animation effect, duration, pause on hover, width of slider.
	 * @todo move this to Rooms Post Type class
	 * 
	 * 
	 *  @param none via $_REQUEST.
	 *  @return array slider_config
	 *  
	 *  Exceptions thrown: database-related exceptions may be thrown by this function. 
	 * 
	 * TODO: This function is deprecated. Moved to rooms post type.
	 */
	
	private static function get_slider_config_meta( $postID ) {

		$slider_config = array();
		
		$meta = get_post_meta( $postID );
		if ( $meta === false) {
			Hospitality_Logger::log_error(__('No metadata for post', GUESTABA_HSP_TEXTDOMAIN));
			return false ;
		}

		$slider_config['post_id'] = $postID;

		/* navigaion true|false */
		$slider_config['navigation_on'] = true;
		
		/* effect */
		$slider_config['effect'] = $meta['meta_room_slider_animation_effect'][0];
		
		/* slide duration, in ms */
		$slider_config['slide_duration'] = $meta['meta_room_slider_animation_speed'][0] ;
		
		/* auto animation */
		$slider_config['auto_animation'] =  ( $meta['meta_room_slider_animation_auto'][0] == "on" ) ? true : false;
		
		/* animation duration, in ms */
		$slider_config['animation_duration'] = $meta['meta_room_slider_animation_duration'][0] ;
		
		/* pause on hover */
		$slider_config['pause_on_hover'] =  ( $meta['meta_room_slider_animation_pause'][0] == "on" ) ? true : false;
		
		$slider_config['width'] = GUESTABA_HSP_SLIDER_WIDTH;
		$slider_config['height'] = GUESTABA_HSP_SLIDER_HEIGHT;
		
		return $slider_config ;	
	
	}
	
	/*
	 * Get and return amenity set for specified post ID. 
	 * 
	 * @param string postID
	 * @return array amenity set list for specified rooms post. 
	 *
	 * @since 1.0.0
	 */
	private static function get_amenity_set_list( $postID ) {
			return Amenity_Sets_Post_Type::get_amenity_list($postID);
	}
	

	/*
	 * Get and return pricing model for specified post ID. 
	 * 
	 * @param string postID
	 * @return array containing pricing model for specified rooms post. 
	 *
	 * @since 1.0.0
	 */
	private static function get_pricing_model( $postID ) {
			return Pricing_Models_Post_Type::get_pricing_model($postID);
	}


	private static function validate_room_location_request() {
		if ( !isset( $_REQUEST['postID'])  ||
		     !isset( $_REQUEST['room_location'])  ||
		     !isset( $_REQUEST['room_location']['address'])  ) {
			return false;
		}
		return true;
	}

	private static function request_to_room_location () {
		$room_location = array(
			'room_id' => wp_kses_post($_REQUEST['postID']),
			'address' => sanitize_text_field( $_REQUEST['room_location']['address']),
			'unit_number' => sanitize_text_field( $_REQUEST['room_location']['unit_number']),
			'status' => sanitize_text_field($_REQUEST['room_location']['status']),
		);

		return $room_location;
	}


	private static function request_to_search_criteria () {

		$search_decoded = json_decode( stripslashes($_REQUEST['searchCriteria']), true, 10 );
		
		if ($search_decoded == null ) {
			throw new Exception(__('Could not JSON decode request.', GUESTABA_HSP_TEXTDOMAIN ) );
		}

		// make sure length of stay is not 0.
		$duration = intval( $search_decoded['lengthOfStayLong']);
		$duration = $duration === 0 ? GUESTABA_MILLISECONDS_PER_DAY : $duration;

		$search_criteria = array(
			'amenities' => array_map( 'sanitize_text_field', $search_decoded['selectedAmenities']),
			'occupants' => intval( $search_decoded['numberOfOccupants']),
			'max_price' => intval( $search_decoded['maximumPrice']),
			'start_time' => intval( $search_decoded['dateOfArrivalLong']) ,
			'duration' => $duration
		);

		return $search_criteria;
	}

	private static function request_to_room_location_availability_request () {

		$search_decoded = json_decode( stripslashes($_REQUEST['searchCriteria']), true, 10 );

		if ($search_decoded == null ) {
			throw new Exception(__('Could not JSON decode request.', GUESTABA_HSP_TEXTDOMAIN ) );
		}

		$search_criteria = array(
			'room_location_id' => intval( $search_decoded['room_location_id']),
			'start_time' => intval( $search_decoded['dateOfArrivalLong']),
			'duration' => intval( $search_decoded['lengthOfStayLong'])
		);

		return $search_criteria;
	}


	private static function request_to_reservation_request () {

		$request_decoded = json_decode( stripslashes($_REQUEST['reservationInfo']), true, 10 );

		if ($request_decoded == null ) {
			throw new Exception(__('Could not JSON decode request.', GUESTABA_HSP_TEXTDOMAIN ) );
		}

		$reservation_request = array(
			'first_name' => sanitize_text_field( $request_decoded['firstName']),
			'last_name' => sanitize_text_field( $request_decoded['lastName']),
			'middle_name' => '',
			'suffix' => '',
			'work_phone' => '',
			'home_phone' => sanitize_text_field( $request_decoded['phone']),
			'mobile_phone' => sanitize_text_field( $request_decoded['mobile']),
			'email' => sanitize_text_field( $request_decoded['email']),
			'address1' => sanitize_text_field( $request_decoded['address1']),
			'address2' => sanitize_text_field( $request_decoded['address2']),
			'city' => sanitize_text_field( $request_decoded['city']),
			'state' => sanitize_text_field( $request_decoded['state']),
			'postal_code' => sanitize_text_field( $request_decoded['zip']),
			'country' => sanitize_text_field( $request_decoded['country']),
			'no_of_adults' => intval( $request_decoded['adults']),
			'no_of_children' => intval( $request_decoded['children']),
			'start_time' => intval( $request_decoded['dateOfArrivalLong']),
			'duration' => intval( $request_decoded['lengthOfStayLong']),
			'room_id' => intval( $request_decoded['selectedRoomID']),
			'payment_method' => sanitize_text_field( $request_decoded['paymentMethod']),
			

		);
		
		// validate fields
		if (	empty( $reservation_request['first_name']) ||
			  	empty( $reservation_request['last_name']) ||
				empty( $reservation_request['address1']) ||
				empty( $reservation_request['city']) ||
				empty( $reservation_request['state']) ||
				empty( $reservation_request['postal_code']) ||
				empty( $reservation_request['country']) || 
				empty( $reservation_request['home_phone'])  ||
			    $reservation_request['duration'] >= GUESTABA_MILLISECONDS_PER_DAY * GUESTABA_RES_MAX_DAYS
		) {
			throw new Exception(__('Reservation request contains invalid field values.', GUESTABA_HSP_TEXTDOMAIN ) );
		}

		return $reservation_request;
	}

	private static function get_locations() {
		return Locations_Post_Type::get_locations();
	}

	/*
	 * Get and return get rooms with the speciifed criteria.
	 *
	 * @param array searchCriteria
	 * @return array containing rooms that meet the specified critera.
	 *
	 * @since 1.0.6
	 */
	private static function search_rooms( $searchCriteria ) {

		$searchCriteria = self::request_to_search_criteria( $searchCriteria );
		return Rooms_Post_Type::get_rooms( $searchCriteria );
	}


	private static function reserve_room( $reservationInfo ) {
		$reservation_request = self::request_to_reservation_request( $reservationInfo );
		return Reservation_Agent::reserve_room( $reservation_request );
	}

	/*
	 * Get and return get rooms with the speciifed criteria that are available during the
	 * time specified in the criteria.
	 *
	 * @param array searchCriteria
	 * @return array containing rooms that meet the specified critera.
	 *
	 * @since 1.0.6
	 */
	private static function get_available_rooms( $searchCriteria ) {

		$searchCriteria = self::request_to_search_criteria( $searchCriteria );
		return Reservation_Agent::get_available_rooms( $searchCriteria );
	}

	private static function get_room_location_availability( $searchCriteria ) {

		$searchCriteria = self::request_to_room_location_availability_request( $searchCriteria );
		return Reservation_Agent::get_room_location_availability( $searchCriteria );
	}

	private static function get_all_rooms() {
		return Reservation_Agent::get_all_rooms();
	}

    private static function gen_demo_data() {
        //  $rooms =  Reservation_Agent::get_all_rooms();
        $demo = new Hospitality_Demo();
        return $demo->gen_demo_data();
    }

	private static function validate_room_location() {

        $roomLocationID = sanitize_text_field($_REQUEST['roomLocationID'] );
        $locationID = sanitize_text_field($_REQUEST['locationID'] );
		$roomNumber = sanitize_text_field($_REQUEST['roomNumber'] );
        return Room_Locations_Post_Type::validate_room_location( $roomLocationID, $locationID, $roomNumber );
		
	}
	/*
	 * Get and return get room locations.
	 *
	 * @param string postID
	 * @return array containing room locations for specified rooms post.
	 *
	 * @since 1.0.0
	 */
	private static function get_room_locations( $postID ) {
		return Room_Locations_Post_Type::get_room_locations($postID);
	}

	/*
	 * Add room location.
	 *
	 * @param string postID
	 * @return a copy of added room post.
	 *
	 * @since 1.0.6
	 */

	private static function add_room_location() {
		$room_location = self::request_to_room_location();
		return Room_Locations_Post_Type::add_room_location( $room_location );
	}
	/*
    * Modify room location.
    *
    * @param string postID
    * @return a copy of modified room post.
    *
    * @since 1.0.6
    */
	private static function modify_room_location( $postID ) {
		return Room_Locations_Post_Type::modify_room_location($postID);
	}

	/*
	* Delete room location.
	*
	* @param string postID
	* @return sucess or failure.
	*
	* @since 1.0.6
	*/
	private static function delete_room_location( $postID ) {
		return Room_Locations_Post_Type::delete_room_location($postID);
	}
	
	private static function register_user() {

		$registration_request = self::request_to_registration_request();
		return Hospitality_User_Meta_Manager::register_user( $registration_request );
		
	}


	private static function request_to_registration_request() {

		$request_decoded = json_decode( stripslashes($_REQUEST['userRegistrationRequest']), true, 10 );

		if ($request_decoded == null ) {
			throw new Exception(__('Could not JSON decode request for registration.', GUESTABA_HSP_TEXTDOMAIN ) );
		}

		$registration_request = array(
			'first_name' => sanitize_text_field( $request_decoded['firstName']),
			'last_name' => sanitize_text_field( $request_decoded['lastName']),
			'phone' => sanitize_text_field( $request_decoded['phone']),
			'mobile' => sanitize_text_field( $request_decoded['mobile']),
			'email' => sanitize_text_field( $request_decoded['email']),
			'address1' => sanitize_text_field( $request_decoded['address1']),
			'address2' => sanitize_text_field( $request_decoded['address2']),
			'city' => sanitize_text_field( $request_decoded['city']),
			'state' => sanitize_text_field( $request_decoded['state']),
			'postal_code' => sanitize_text_field( $request_decoded['zip']),
			'country' => sanitize_text_field( $request_decoded['country']),


		);

		return $registration_request;
	}

	/*
	 * Provides object access to exec request static function. 
	 * 
	 * @param none
	 * @return non
	 * @see execute_request()
	 */
	
	public function hospitality_ajax() {
		self::execute_request();
	}
	
}
?>