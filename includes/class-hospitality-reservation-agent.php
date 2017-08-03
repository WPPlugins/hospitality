<?php


use Omnipay\Omnipay;

/**
 *
 */
class Reservation_Agent {


	/**
	 * Reservation_Agent constructor.
	 */
	public function __construct() {
	}
	
	public static function reserve_room( $reservation_request ) {

		$room_id = $reservation_request['room_id'];
		$start_time = $reservation_request['start_time'];
		$duration = $reservation_request['duration'];

		// Double check to make sure room is not reserved.
		if ( count( Room_Locations_Post_Type::get_available_room_locations( $room_id, $start_time, $duration )) == 0 ) {
			throw new Exception(__('Reservation request for room with no available locations.', GUESTABA_HSP_TEXTDOMAIN));
		}


		// Get payment amount based on start time and duration. 
		$pricing= Rooms_Post_Type::get_reservation_pricing( $room_id , $start_time, $duration );
		
		// Set payment amount in reservation request.
		$reservation_request['room_amount'] = $pricing['room_amount'];
		$reservation_request['fee_amount'] = $pricing['fee_amount'];
		$reservation_request['tax_amount'] = $pricing['tax_amount'];
		$reservation_request['payment_amount']  = $pricing['price'];
		$reservation_request['payment_status'] = Reservations_Post_Type::PAYMENT_STATUS_PENDING;
		// TODO: constant for status.
		$reservation_request['status'] = 'confirmed';


		// Get available room location and set room_location_id. 
		$room_location = Room_Locations_Post_Type::get_next_available_room_location( $room_id, $start_time, $duration );
		$reservation_request['room_location_id'] = $room_location['id'];
		$reservation_request['title'] = $reservation_request['last_name'] . ' ' . $reservation_request['first_name'] . ' ' . $reservation_request['start_time'];

		// TODO: Reservation notes could be placed here. 
		$reservation_request['description'] = '';

		// Set when request response is redirect. It is used to retrieve reservation.
		$reservation_request['token'] = '';

		// Process payment goes here. If process payment succeeds, add reservation, payment status paid,
		// and return confirmation. 
		// Otherwise, set payment status to pending. return payment process info.
		$response = self::process_payment( $reservation_request );
		

		if ( $response['status'] == Payment_Gateway::PROCESS_STATUS_REDIRECT ) {
			$reservation_request['payment_data'] = $response['data'];
			$reservation_request['token'] = $response['token'];
			// Note: payment status is set to pending in reservation_request['payment_status'] above.
			$reservation = Reservations_Post_Type::add_reservation( $reservation_request);
			$response['pending_reservation'] = $reservation;
			return $response ;
		}
		else if (  $response['status'] == Payment_Gateway::PROCESS_STATUS_SUCCESS ) {
			if ($reservation_request['payment_method'] == "offline" ) {
				$reservation_request['payment_status'] = Reservations_Post_Type::PAYMENT_STATUS_PENDING;
			}
			else {
				$reservation_request['payment_status'] = Reservations_Post_Type::PAYMENT_STATUS_PAID;
			}
			$reservation = Reservations_Post_Type::add_reservation( $reservation_request );
			self::send_confirmation_email( $reservation['id'] );


		} else if ( $response['status'] == Payment_Gateway::PROCESS_STATUS_OFFLINE  ) {
			$reservation_request['payment_status'] = Reservations_Post_Type::PAYMENT_STATUS_PENDING_OFFLINE;
			$reservation = Reservations_Post_Type::add_reservation( $reservation_request );
		}


		// TODO: send mail here

		// Reservation returned as confirmation.
		return $reservation;

		
	}

	public static function process_payment( $reservation_request ) {
		$gateway = Payment_Gateway_Factory::create_payment_gateway( $reservation_request['payment_method']);
		$gateway->initialize_payment( $reservation_request );
		return $gateway->process_payment();
	}

	public static function confirm_payment() {

		$payment_method = Payment_Gateway_Factory::detect_confirmation_payment_method();
		$gateway = Payment_Gateway_Factory::create_payment_gateway( $payment_method );
		$response =  $gateway->confirm_payment();
		
		if ( $response['status'] == Payment_Gateway::PROCESS_STATUS_SUCCESS )  {
			if ( ! Reservations_Post_Type::update_payment_status( $response['reservation_id'], Reservations_Post_Type::PAYMENT_STATUS_PAID) ) {
				throw new Exception(__('Could not update payment status after confirmation redirect.', GUESTABA_HSP_TEXTDOMAIN ) );
			}

			self::send_confirmation_email( $response['reservation_id'] );
		}
		
		return $response ;
	}
	


	public static function get_available_rooms( $criteria ) {

		$start_time = $criteria['start_time'];
		$duration = $criteria['duration'];


		// Get rooms that meet occupancy, amenity, and price requirements
		$rooms = Rooms_Post_Type::get_rooms( $criteria );

		$available_rooms = array();

		// From those rooms, determine which have available locations.
		foreach ( $rooms as $room) {

			$available_room_locations = Room_Locations_Post_Type::get_available_room_locations( $room['id'], $start_time, $duration);
			if ( count( $available_room_locations ) > 0 ) {
				$available_rooms[] = $room;
			}
		}

		return $available_rooms;

	}
	
	public static function get_all_rooms() {
		return Rooms_Post_Type::get_all_rooms();
	}

	public static function get_room_location_availability( $criteria ) {

		$start_time = $criteria['start_time'];
		$duration = $criteria['duration'];
		$room_location_id = $criteria['room_location_id'];

		$is_available =  Room_Locations_Post_Type::room_location_is_available( $room_location_id, $start_time, $duration );

		$availbility_info = array(
			'is_available' => $is_available
		);

		return $availbility_info;

	}
	
	

	public static function check_reservation_overlap( $reservations, $start_time, $duration ) {

		$end_time = $start_time + $duration;

		foreach ( $reservations as $reservation ) {
			$res_start_time = $reservation['start_time'];
			$res_end_time = $res_start_time + $reservation['duration'];

			if ( $end_time <= $res_start_time || $start_time >= $res_end_time ) {
				continue;
			}
			else {
				// There is overlap.
				return true;
			}
		}

		// No overlapping reservation
		return false;
	}


	private static function send_confirmation_email( $reservation_id ) {
		
		$reservation = Reservations_Post_Type::get_reservation( $reservation_id) ;

		$options = get_option( GUESTABA_HSP_OPTIONS_NAME);
		
		$to_address = $reservation['email'];
		$start_time = $reservation['start_time'];
		// TODO: define constants, no of milliseconds per day
		$nights = $reservation['duration'] / 86400000 ;

		// TODO: localize date format, move to a utility class.
		$timestamp = $start_time / 1000;
		$date_str = get_date_from_gmt(date( 'Y-m-d H:i:s', $timestamp ), 'M d, Y h:i A T' );

		// TODO: localize currency symbol position
		$amount_str = $options['hsp_currency_symbol'] . $reservation['payment_amount'];
		$confirmation_number = $reservation['id'];

		$subject = get_bloginfo('name') . ' '. __('Reservation Confirmation', GUESTABA_HSP_TEXTDOMAIN);

		$message = $options['hsp_confirmation_email_message'];
		if ( empty($message) ) {
			$message =  __('Your reservation is confirmed.', GUESTABA_HSP_TEXTDOMAIN) ;
		}

		$message .= PHP_EOL . __('Confirmation number:', GUESTABA_HSP_TEXTDOMAIN) . $confirmation_number . PHP_EOL .
					__('Nights:', GUESTABA_HSP_TEXTDOMAIN) . $nights . PHP_EOL .
					__('Your date of arrival:', GUESTABA_HSP_TEXTDOMAIN) . $date_str . PHP_EOL .
					__('Payment amount:', GUESTABA_HSP_TEXTDOMAIN) . $amount_str ;

		if ( ! wp_mail( $to_address, $subject, $message )) {
			error_log(__FILE__ . ':' . __LINE__ . __('Attempt to send mail failed.') );
		}

	}

}