<?php

/**
 *
 */
class Reservations_Post_Type {

	const PAYMENT_STATUS_PENDING = 'pending';
	const PAYMENT_STATUS_PAID = 'paid';
	const PAYMENT_STATUS_CANCELLED = 'cancelled';
	const PAYMENT_STATUS_PENDING_OFFLINE = 'pending_offline';



	/**
	 *  String to define post type name.
	 *  @since	1.0.0
	 *  @access	protected
	 *  @var String  $post_type  Stores post_type name
	 */
	protected $post_type ;

	/**
	 * Array for storing UI labels for Reservations custom post type
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var     array    $labels   Stores UI labels for Reservation CPT
	 */
	protected $labels;

	/**
	 * Array for storing argument passed to register_post_type
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var     array    $args   Stores UI labels for Reservation CPT
	 */
	protected $args;

	/**
	 * Constructor for Reservation Post Type
	 * Initializes labels and args for registration.
	 * @since    1.0.0
	 */

	public function __construct() {

		$this->post_type = 'reservations';

		$theme = wp_get_theme();
		$text_domain = $theme->get('TextDomain');

		$this->labels = array(
			'name'                => __( 'Reservation Listings', $text_domain ),
			'singular_name'       => __( 'Reservation', $text_domain ),
			'menu_name'           => __( 'Reservations', $text_domain ),
			'parent_item_colon'   => __( 'Parent Reservation:', $text_domain ),
			'all_items'           => __( 'All Reservations', $text_domain ),
			'view_item'           => __( 'View Reservation', $text_domain ),
			'add_new_item'        => __( 'Add New Reservation', $text_domain ),
			'add_new'             => __( 'Add New', $text_domain ),
			'edit_item'           => __( 'Edit Reservation', $text_domain ),
			'update_item'         => __( 'Update Reservation', $text_domain ),
			'search_items'        => __( 'Search Reservations', $text_domain ),
			'not_found'           => __( 'No reservations found', $text_domain ),
			'not_found_in_trash'  => __( 'No reservations found in Trash', $text_domain )
		);

		$this->args = array(
			'label'               => __( 'Reservations', $text_domain ),
			'labels'              => $this->labels,
			'description'         => __('Reservation description goes here.', GUESTABA_HSP_TEXTDOMAIN ),
			'supports'            => array( 'title', 'excerpt', 'author', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', 'thumbnail' ),
			'hierarchical'        => true,
			'public'              => true,

			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,

			'query_var'           => true,
			'publicly_queryable'  => true,

			'exclude_from_search' => false,
			'has_archive'         => true,

			'can_export'          => true,
			'menu_position'       => 5,
			'rewrite'             => array(
				'slug'            => 'reservations',
				'with_front'      => true,
				'pages'           => true,
				'feeds'           => true,
			),
			'capability_type'     => 'post',
			'taxonomies'          => array( 'category', 'post_tag' )
		);

	}

	/*
	 * Register post type
	 *
	 * @since 1.0.0
	 *
	 * @param none
	 * @return void
	 */
	public function register() {
		register_post_type( $this->post_type, $this->args);
	}


	/*
	 * Remove post actions
	 *
	 * @since 1.0.0
	 *
	 * @param none
	 * @return void
	 */
	public function remove_post_actions($actions) {
		if ( 'reservations' === get_post_type() ) {
			unset( $actions['trash'] );
		}
		return $actions;;
	}


	/*
	 * Get page by slug. Post support function.
	 *
	 * @since 1.0.0
	 */
	public function get_page_by_slug($page_slug, $output = OBJECT, $post_type = 'page' ) {
		global $wpdb;
		$page = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s", $page_slug, $post_type ) );
		if ( $page )
			return get_page($page, $output);
		return null;
	}


	/*
	 * Add to query. Post support function.
	 *
	 * @since 1.0.0
	 */
	public function add_to_query( $query ) {
		if ( is_home() && $query->is_main_query()  && $query->is_search  && $query->is_category )
			$query->set( 'post_type', array( 'post', 'page', 'reservations' ) );
		return $query;
	}

	/*
	 * Query post type. Post support function.
	 *
	 * @since 1.0.0
	 */
	public function query_my_post_types( &$query ) {
		// Do this for all category and tag pages, can be extended to is_search() or is_home() ...
		if ( is_category() || is_tag() ) {
			$post_type = $query->get( 'post_type' );
			// ... if no post_type was defined in query then set the defaults ...
			if ( empty( $post_type ) ) {
				$query->set( 'post_type', array(
					'post',
					'reservations'
				) );
			}
		}
	}


	/*
	 * Get post type templates. Post support function.
	 *
	 *
	 * @since 1.0.0
	 */
	public function get_custom_post_type_template($template_path) {

		if ( get_post_type() == 'reservations' ) {
			if ( is_single() ) {
				// checks if the file exists in the theme first,
				// otherwise serve the file from the plugin
				if ( $theme_file = locate_template( array ( 'single-reservations.php' ) ) ) {
					$template_path = $theme_file;
				} else {
					$template_path = trailingslashit( dirname( plugin_dir_path( __FILE__ ) ) ). 'single-reservations.php';
				}
			}
			elseif ( is_archive() ) {
				if ( $theme_file = locate_template( array ( 'archive-reservations.php' ) ) ) {
					$template_path = $theme_file;
				} else {
					$template_path = trailingslashit( dirname( plugin_dir_path( __FILE__ ) ) ) . 'archive-reservations.php';
				}
			}
		}
		return $template_path;
	}




	/*
	 * Truncate post. Post support function.
	 *
	 * Probably never used for this post type.
	 *
	 * @since 1.0.0
	 */
	public function truncate_post( $amount, $echo = true, $post = '' ) {
		global $shortname;
		if ( '' == $post ) global $post;
		$post_excerpt = '';
		$post_excerpt = apply_filters( 'the_excerpt', $post->post_excerpt );
		if ( 'on' == et_get_option( $shortname . '_use_excerpt' ) && '' != $post_excerpt ) {
			if ( $echo ) echo $post_excerpt;
			else return $post_excerpt;
		} else {

			if ( 'reservations' == get_post_type() ) {
				$truncate = get_post_meta($post->ID, 'meta_reservation_desc', true);
			} else {
				$truncate = $post->post_content;
			}

			// remove caption shortcode from the post content
			$truncate = preg_replace('@\[caption[^\]]*?\].*?\[\/caption]@si', '', $truncate);
			// apply content filters
			$truncate = apply_filters( 'the_content', $truncate );
			// decide if we need to append dots at the end of the string
			if ( strlen( $truncate ) <= $amount ) {
				$echo_out = '';
			} else {
				$echo_out = '...';
				// $amount = $amount - 3;
			}
			// trim text to a certain number of characters, also remove spaces from the end of a string ( space counts as a character )
			$truncate = rtrim( wp_trim_words( $truncate, $amount, '' ) );
			// remove the last word to make sure we display all words correctly
			if ( '' != $echo_out ) {
				$new_words_array = (array) explode( ' ', $truncate );
				array_pop( $new_words_array );
				$truncate = implode( ' ', $new_words_array );
				// append dots to the end of the string
				$truncate .= $echo_out;
			}
			if ( $echo ) echo $truncate;
			else return $truncate;
		};
	}



	public static function get_reservations( $room_location_ID ) {

		$reservations = array();

		$args = array(
			'post_type'      => 'reservations',
			'post_status'    => 'publish',
			'meta_query'	 => array(
				array( 'key' => 'room_location_id',
					'value' => $room_location_ID,
					'type' => 'numeric',
					'compare' => '=')
			),
			'posts_per_page' => - 1
		);

		$res_query = new WP_Query( $args );


		if ( $res_query->have_posts() ) {
			while ( $res_query->have_posts() ) : $res_query->the_post();
				$reservation_id = get_the_ID();
				$reservations[] = self::get_reservation( $reservation_id );


			endwhile;
		}

		wp_reset_postdata();
		
		return $reservations;

	}


	public static function add_reservation( $reservation ) {

		$post_id = wp_insert_post(
			array(
				'post_title' => $reservation['title'],
				'post_content' => $reservation['description'],
				'post_type' => 'reservations',
				'post_status' => 'publish'
			));


		if ( $post_id !== false ) {

			if ( update_post_meta( $post_id, 'first_name', $reservation['first_name'] ) &&
			     update_post_meta( $post_id, 'last_name', $reservation['last_name'] ) &&
			     update_post_meta( $post_id, 'middle_name', $reservation['middle_name'] ) &&
			     update_post_meta( $post_id, 'suffix', $reservation['suffix'] ) &&
			     update_post_meta( $post_id, 'address1', $reservation['address1'] ) &&
			     update_post_meta( $post_id, 'address2', $reservation['address2'] ) &&
			     update_post_meta( $post_id, 'city', $reservation['city'] ) &&
			     update_post_meta( $post_id, 'state', $reservation['state'] ) &&
			     update_post_meta( $post_id, 'postal_code', $reservation['postal_code'] ) &&
			     update_post_meta( $post_id, 'home_phone', $reservation['home_phone'] ) &&
			     update_post_meta( $post_id, 'mobile_phone', $reservation['mobile_phone'] ) &&
			     update_post_meta( $post_id, 'work_phone', $reservation['work_phone'] ) &&
			     update_post_meta( $post_id, 'email', $reservation['email'] ) &&
			     update_post_meta( $post_id, 'no_of_adults', $reservation['no_of_adults'] ) &&
			     update_post_meta( $post_id, 'no_of_children', $reservation['no_of_children'] ) &&
			     update_post_meta( $post_id, 'payment_status', $reservation['payment_status'] ) &&
			     update_post_meta( $post_id, 'room_amount', $reservation['room_amount'] ) &&
				 update_post_meta( $post_id, 'fee_amount', $reservation['fee_amount'] ) &&
				 update_post_meta( $post_id, 'tax_amount', $reservation['tax_amount'] ) &&
				 update_post_meta( $post_id, 'payment_amount', $reservation['payment_amount'] ) &&
				 update_post_meta( $post_id, 'payment_data', $reservation['payment_data'] ) &&
				 update_post_meta( $post_id, 'room_location_id', $reservation['room_location_id'] ) &&
			     update_post_meta( $post_id, 'start_time', $reservation['start_time'] ) &&
			     update_post_meta( $post_id, 'duration', $reservation['duration'] ) &&
			     update_post_meta( $post_id, 'status', $reservation['status'])&&
				 update_post_meta( $post_id, 'token', $reservation['token'])
			) {


				return self::get_reservation( $post_id );

			}
			else {
				error_log( __FILE__  . ',' . __LINE__ . ':'.  __('Could not add reservation. Update post meta failed', GUESTABA_HSP_TEXTDOMAIN));
				return false;

			}
		}

		error_log( __FILE__  . ',' . __LINE__ . ':'.  __('Could add reservation. Could not insert new post.', GUESTABA_HSP_TEXTDOMAIN));


		return false;

	}


	public static function update_payment_status( $reservation_id, $payment_status ) {

		$check_reservation = self::get_reservation( $reservation_id );
		
		$status = false;

		if ( $check_reservation !== false ) {

			switch ( $payment_status ) {
				case Reservations_Post_Type::PAYMENT_STATUS_PAID:
				case Reservations_Post_Type::PAYMENT_STATUS_CANCELLED:
				case Reservations_Post_Type::PAYMENT_STATUS_PENDING:
				case Reservations_Post_Type::PAYMENT_STATUS_PENDING_OFFLINE:
					if ( update_post_meta( $reservation_id, 'payment_status', $payment_status ) ) {
						$status = true;	
					} 
					else {
						// TODO: Throw an exception here.
						error_log( __FILE__  . ',' . __LINE__ . ':'.  __('Could not update reservation payment status, post ID = ' . $reservation_id, GUESTABA_HSP_TEXTDOMAIN));
					}
					break;
				default:
					// TODO: Throw and exception here. Remove throw exception in calling function(s).
					error_log( __FILE__  . ',' . __LINE__ . ':'.  __('Invalid payment status, post ID = ' . $reservation_id, GUESTABA_HSP_TEXTDOMAIN));
					break;
			}

		}
		
		return $status;

	}





	public static function modify_reservation( $reservation ) {

		$check_reservation = self::get_reservation( $reservation['id'] );

		if ( $check_reservation !== false ) {

			$post_id = $reservation['id'];

			if ( update_post_meta( $post_id, 'first_name', $reservation['first_name'] ) &&
			     update_post_meta( $post_id, 'last_name', $reservation['last_name'] ) &&
			     update_post_meta( $post_id, 'middle_name', $reservation['middle_name'] ) &&
			     update_post_meta( $post_id, 'suffix', $reservation['suffix'] ) &&
			     update_post_meta( $post_id, 'address1', $reservation['address1'] ) &&
			     update_post_meta( $post_id, 'address2', $reservation['address2'] ) &&
			     update_post_meta( $post_id, 'city', $reservation['city'] ) &&
			     update_post_meta( $post_id, 'state', $reservation['state'] ) &&
			     update_post_meta( $post_id, 'postal_code', $reservation['postal_code'] ) &&
			     update_post_meta( $post_id, 'home_phone', $reservation['home_phone'] ) &&
			     update_post_meta( $post_id, 'mobile_phone', $reservation['mobile_phone'] ) &&
			     update_post_meta( $post_id, 'work_phone', $reservation['work_phone'] ) &&
			     update_post_meta( $post_id, 'email', $reservation['email'] ) &&
			     update_post_meta( $post_id, 'no_of_adults', $reservation['no_of_adults'] ) &&
			     update_post_meta( $post_id, 'no_of_children', $reservation['no_of_children'] ) &&
			     update_post_meta( $post_id, 'payment_status', $reservation['payment_status'] ) &&
			     update_post_meta( $post_id, 'payment_amount', $reservation['payment_amount'] ) &&
				 update_post_meta( $post_id, 'payment_data', $reservation['payment_data'] ) &&
				 update_post_meta( $post_id, 'room_location_id', $reservation['room_location_id'] ) &&
			     update_post_meta( $post_id, 'start_time', $reservation['start_time'] ) &&
			     update_post_meta( $post_id, 'duration', $reservation['duration'] ) &&
			     update_post_meta( $post_id, 'status', $reservation['status']) &&
				 update_post_meta( $post_id, 'token', $reservation['token'])

			) {
				return self::get_reservation( $post_id );

			}
			else {
				error_log( __FILE__  . ',' . __LINE__ . ':'.  __('Could update reservation, post ID = ' . $reservation['id'], GUESTABA_HSP_TEXTDOMAIN));
				return false;
			}
		}

		error_log(  __FILE__  .',' . __LINE__ . ':'. __('Reservation does not exist, post ID = ' . $reservation['id'], GUESTABA_HSP_TEXTDOMAIN));

		return false;


	}

	public static function delete_reservation( $reservation_ID ) {

		$check_reservation = self::get_reservation( $reservation_ID );

		if ( $check_reservation !== false ) {

			$post_id = $reservation_ID;

			if ( wp_delete_post( $post_id ) ) {
				return true;
			}
			else {
				error_log( __FILE__  . ',' . __LINE__ . ':'.  __('Could not delete reservation, post ID = ' . $reservation_ID, GUESTABA_HSP_TEXTDOMAIN));
				return false;

			}
		}
		else {
			error_log(  __FILE__  . ',' . __LINE__ . ':'. __('Reservation does not exist, post ID = ' . $reservation_ID, GUESTABA_HSP_TEXTDOMAIN));
		}

		return false;


	}

	public static function get_reservation( $post_id ) {

		$post = get_post( $post_id );

		if ( $post != null ) {
			$reservation = self::get_array( $post );
			return $reservation;
		}
		else {
			return false;
		}
	}

	public static function get_reservation_by_token ( $token ) {

		$args = array(
			'post_type'      => 'reservations',
			'post_status'    => 'publish',
			'meta_query'	 => array(
				array(	 'key' => 'token',
						'value' => $token
				)
			),
			'posts_per_page' => - 1
		);

		global $post;
		$reservation = false;
		
		$res_query = new WP_Query( $args );
		while ( $res_query->have_posts() ) : $res_query->the_post();
			$reservation = self::get_array( $post );
		endwhile;
		wp_reset_postdata();
		
		return $reservation;

	}


	public static function get_array( $post ) {


		$reservation = array(
			'id' => $post->ID,
			'title' => $post->post_title,
			'description' => $post->post_content,
			'date' => $post->post_date,
			'timestamp' => get_post_time('U', true, $post),
			'first_name' => get_post_meta( $post->ID, 'first_name', true),
			'last_name' => get_post_meta( $post->ID, 'last_name', true),
			'middle_name' => get_post_meta( $post->ID, 'midde_name', true),
			'suffix' => get_post_meta( $post->ID, 'suffix', true),
			'address1' => get_post_meta( $post->ID, 'address1', true),
			'address2' => get_post_meta( $post->ID, 'address2', true),
			'city' => get_post_meta( $post->ID, 'city', true),
			'state' => get_post_meta( $post->ID, 'state', true),
			'post_code' => get_post_meta( $post->ID, 'postal_code', true),
			'mobile_phone' => get_post_meta( $post->ID, 'mobile_phone', true),
			'home_phone' => get_post_meta( $post->ID, 'home_phone', true),
			'work_phone' => get_post_meta( $post->ID, 'work_phone', true),
			'email' => get_post_meta( $post->ID, 'email', true),
			'no_of_adults' => get_post_meta( $post->ID, 'no_of_adults', true),
			'no_of_children' => get_post_meta( $post->ID, 'no_of_children', true),
			'payment_status' => get_post_meta( $post->ID, 'payment_status', true),
			'room_amount' => get_post_meta( $post->ID, 'room_amount', true),
			'tax_amount' => get_post_meta( $post->ID, 'tax_amount', true),
			'fee_amount' => get_post_meta( $post->ID, 'fee_amount', true),
			'payment_amount' => get_post_meta( $post->ID, 'payment_amount', true),
			'payment_data' => get_post_meta( $post->ID, 'payment_data', true),
			'room_location_id' => get_post_meta( $post->ID, 'room_location_id', true),
			'start_time' => get_post_meta( $post->ID, 'start_time', true),
			'duration' => get_post_meta( $post->ID, 'duration', true),
			'status' => get_post_meta( $post->ID, 'status', true),
			'token' => get_post_meta( $post->ID, 'token', true)



		);

		return $reservation;
	}

	public static function get_all_reservations() {
		$reservations = array();

		$args = array(
			'post_type'      => 'reservations',
			'post_status'    => 'publish',
			'posts_per_page' => - 1
		);

		$res_query = new WP_Query( $args );


		if ( $res_query->have_posts() ) {
			while ( $res_query->have_posts() ) : $res_query->the_post();
				$reservation_id = get_the_ID();
				$reservations[] = self::get_reservation( $reservation_id );


			endwhile;
		}

		wp_reset_postdata();

		return $reservations;
	}

	public static function get_closed_reservations() {

		$reservations = self::get_all_reservations();

		$now = time() * 1000;

		$closed = array();

		foreach ( $reservations as $reservation ) {
			$start = intval( $reservation['start_time']) ;
			$end = $start + intval( $reservation['duration'] );
			if ( $now > $end ) {
				$closed[] = $reservation;
			}
		}

		return $closed;
	}

	public static function get_current_reservations() {

			$reservations = self::get_all_reservations();

			$now = time() * 1000;

			$current = array();

			foreach ( $reservations as $reservation ) {
				$start = intval( $reservation['start_time'] );
				$end = $start + intval( $reservation['duration'] );
				if ( $now >= $start && $now <= $end ) {
					$current[] = $reservation;
				}
			}

			return $current;
	}




	public static function get_future_reservations() {

		$reservations = self::get_all_reservations();

		$now = time() * 1000;

		$future = array();

		foreach ( $reservations as $reservation ) {
			$start = intval( $reservation['start_time'] );
			if ( $now < $start  ) {
				$future[] = $reservation;
			}
		}

		return $future;

	}



	// TODO: The functions from here on down fall into the realm of presentation. Move them to an apppropriate class.
	public function init_custom_columns ( $columns ) {

			$columns = array(
				'cb' => '<input type="checkbox" />',
				'title' => __( 'Reservation', GUESTABA_HSP_TEXTDOMAIN ),
				'confirmation_number' => __('Confirmation #', GUESTABA_HSP_TEXTDOMAIN ),
				'start_time' => __( 'Arrival Date', GUESTABA_HSP_TEXTDOMAIN  ),
				'payment_status' => __( 'Paid',GUESTABA_HSP_TEXTDOMAIN  ),
				'date' => __( 'Date', GUESTABA_HSP_TEXTDOMAIN  )
			);

			return $columns;
	}

	public function init_sortable_columns( $columns ) {

		$columns['start_time']  = 'start_time';
		$columns['confirmation_number']  = 'confirmation_number';
		$columns['payment_status']  = 'payment_status';


		return $columns;

	}

	public function init_edit_sort() {
		add_filter( 'request', array( $this, 'sort_editor_list') );
	}

	public function sort_editor_list( $vars ) {

		/* Check if we're viewing the 'reservations' post type. */
		if ( isset( $vars['post_type'] ) && 'reservations' == $vars['post_type'] ) {

			/* Check if 'orderby' is set to 'start_time'. */
			if ( isset( $vars['orderby'] ) ) {

				switch ($vars['orderby']) {

					case 'start_time':
						$vars = array_merge(
							$vars,
							array(
								'meta_key' => 'start_time',
								'orderby' => 'meta_value_num'
							)
						);
						break;


					case 'confirmation_number':
						$vars = array_merge(
							$vars,
							array(
								'orderby' => 'ID'
							)
						);
						break;

					case 'payment_status':
						$vars = array_merge(
							$vars,
							array(
								'meta_key' => 'payment_status',
								'orderby' => 'meta_value'
							)
						);
						break;

					default:
						break;
				}


			}
		}

		return $vars;
	}


	public function output_custom_columns( $column ) {

		global $post;

		switch( $column ) {

			case 'start_time':
				$start_time = intval( get_post_meta( $post->ID, 'start_time', true));
				$tz = get_option('timezone_string');
				date_default_timezone_set( $tz );
				$date_format = __('M d, Y', GUESTABA_HSP_TEXTDOMAIN);
				$date_str = date( $date_format, $start_time / 1000 );
				echo $date_str;
				break;

			case 'payment_status':
				echo get_post_meta( $post->ID, 'payment_status', true );
				break;

			case 'confirmation_number':
				echo  $post->ID;
				break;

			default:
				break;
		}

	}

	public function add_active_status_filter_to_admin() {

		global $post_type;
		if ($post_type == 'reservations') {

			$selected_value = '';
			if (isset($_GET['res_active_status'])) {
				$selected_value = sanitize_text_field($_GET['res_active_status']);
			}
			
			$closed_selected = '';
			$current_selected = '';
			$future_selected = '';
			$all_selected = '';
			
			switch( $selected_value ) {
				case 'closed':
					$closed_selected = 'selected';
					break;
				
				case 'current':
					$current_selected = 'selected';
					break;
				
				case 'future':
					$future_selected = 'selected';
					break;
				
				default:
					$all_selected = 'selected';
					break;
			}

			$output = '
				<select name="res_active_status" id="res_active_status">
					<option ' . $all_selected . ' value="0">' . __('All Reservations', GUESTABA_HSP_TEXTDOMAIN ) . '</option>
					<option ' . $closed_selected . '  value="closed">' . __('Closed', GUESTABA_HSP_TEXTDOMAIN ) . '</option>
					<option ' . $current_selected . '  value="current">' . __('Current', GUESTABA_HSP_TEXTDOMAIN ) . '</option>
					<option ' . $future_selected . '  value="future">' . __('Future', GUESTABA_HSP_TEXTDOMAIN ) . '</option>
				</select>';
			
			echo $output;
			
		}
	}

	public function filter_by_active_status( $query ){


		global $post_type, $pagenow;

		//if we are currently on the edit screen of the post type listings
		if($pagenow == 'edit.php' && $query->is_main_query() && $post_type == 'reservations'){


			//if we are currently on the edit screen of the post type listings
		// if( $query->post_type == 'reservations'){
			if(isset($_GET['res_active_status'])){

				//get the desired post format
				$active_status = sanitize_text_field($_GET['res_active_status']);
				//if the post format is not 0 (which means all)
				if( is_numeric( $active_status) && $active_status == 0 ) {
					return;
				}
				else {

					switch ( $active_status ) {
						case 'closed':
							$reservations = self::get_closed_reservations();
							break;

						case 'current':
							$reservations = self::get_current_reservations();
							break;

						case 'future':
							$reservations = self::get_future_reservations();
							break;

						default:
							break;
					}

					$reservation_ids = array();

					if ( count( $reservations) > 0 ) {
						foreach ( $reservations as $reservation ) {
							$reservation_ids[] = $reservation['id'];
						}
					}
					else {
						$reservation_ids[] = -1;
					}



					$query->query_vars['post__in'] = $reservation_ids;

				}


			}
		}
	}

	public function filter_by_payment_status( $query ){


		global $post_type, $pagenow;

		//if we are currently on the edit screen of the post type listings
		if($pagenow == 'edit.php' && $query->is_main_query() && $post_type == 'reservations'){


			//if we are currently on the edit screen of the post type listings
			// if( $query->post_type == 'reservations'){
			if(isset($_GET['res_payment_status'])){

				//get the desired post format
				$payment_status = sanitize_text_field($_GET['res_payment_status']);
				//if the post format is not 0 (which means all)
				if( is_numeric( $payment_status) && $payment_status == 0 ) {
					return;
				}
				else {
					
					$query->query_vars['meta_query'] = array(
						array(
							'key' => 'payment_status',
							'value' => $payment_status,
							'compare' => '='
						)
					);
					
				}
				
			}
		}
	}

	public function add_payment_status_filter_to_admin() {

		global $post_type;
		if ($post_type == 'reservations') {

			$selected_value = '';
			if (isset($_GET['res_payment_status'])) {
				$selected_value = sanitize_text_field($_GET['res_payment_status']);
			}

			$pending_selected = '';
			$paid_selected = '';
			$cancelled_selected = '';
			$all_selected = '';

			switch( $selected_value ) {
				case Reservations_Post_Type::PAYMENT_STATUS_PENDING:
					$pending_selected = 'selected';
					break;

				case Reservations_Post_Type::PAYMENT_STATUS_PAID;
					$paid_selected = 'selected';
					break;

				case Reservations_Post_Type::PAYMENT_STATUS_CANCELLED;
					$cancelled_selected = 'selected';
					break;

				default:
					$all_selected = 'selected';
					break;
			}

			$output = '
				<select name="res_payment_status" id="res_payment_status">
					<option ' . $all_selected . ' value="0">' . __('All Pymt Status', GUESTABA_HSP_TEXTDOMAIN ) . '</option>
					<option ' . $paid_selected . '  value="paid">' . __('Paid', GUESTABA_HSP_TEXTDOMAIN ) . '</option>
					<option ' . $pending_selected . '  value="pending">' . __('Pending', GUESTABA_HSP_TEXTDOMAIN ) . '</option>
					<option ' . $cancelled_selected . '  value="cancelled">' . __('Cancelled', GUESTABA_HSP_TEXTDOMAIN ) . '</option>
				</select>';

			echo $output;

		}
	}


}


