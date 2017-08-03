<?php

/**
 * Created by PhpStorm.
 * User: weskempferjr
 * Date: 3/2/16
 * Time: 8:20 PM
 */
class Room_Locations_Post_Type {

	/**
	 *  String to define post type name.
	 *  @since	1.0.5
	 *  @access	protected
	 *  @var String  $post_type  Stores post_type name
	 */
	protected $post_type ;

	/**
	 * Array for storing UI labels for Room Locations custom post type
	 *
	 * @since    1.0.5
	 * @access   protected
	 * @var     array    $labels   Stores UI labels for Room Location CPT
	 */
	protected $labels;

	/**
	 * Array for storing argument passed to register_post_type
	 *
	 * @since    1.0.5
	 * @access   protected
	 * @var     array    $args   Stores UI labels for Room Location CPT
	 */
	protected $args;

	/**
	 * Constructor for Room Location Post Type
	 * Initializes labels and args for registration.
	 * @since    1.0.0
	 */

	public function __construct() {

		$this->post_type = 'room-locations';

		$theme = wp_get_theme();
		$text_domain = $theme->get('TextDomain');

		$this->labels = array(
			'name'                => __( 'Room Locations Listings', $text_domain ),
			'singular_name'       => __( 'Room Location', $text_domain ),
			'menu_name'           => __( 'Room Locations', $text_domain ),
			'parent_item_colon'   => __( 'Parent Room Location:', $text_domain ),
			'all_items'           => __( 'Room Locations', $text_domain ),
			'view_item'           => __( 'View Room Location', $text_domain ),
			'add_new_item'        => __( 'Add New Room Location', $text_domain ),
			'add_new'             => __( 'Add New', $text_domain ),
			'edit_item'           => __( 'Edit Room Location', $text_domain ),
			'update_item'         => __( 'Update Room Location', $text_domain ),
			'search_items'        => __( 'Search Room Locations', $text_domain ),
			'not_found'           => __( 'No room locations found', $text_domain ),
			'not_found_in_trash'  => __( 'No room locations found in Trash', $text_domain )
		);

		$this->args = array(
			'label'               => __( 'Room Locations', $text_domain ),
			'labels'              => $this->labels,
			'description'         => __('Room Location description goes here.', GUESTABA_HSP_TEXTDOMAIN ),
			'supports'            => array( 'title', 'excerpt', 'author', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', 'thumbnail' ),
			'hierarchical'        => true,
			'public'              => true,

			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=rooms',
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,

			'query_var'           => true,
			'publicly_queryable'  => true,

			'exclude_from_search' => true,
			'has_archive'         => false,

			'can_export'          => true,
			'menu_position'       => 5,
			'rewrite'             => array(
				'slug'            => 'room-locations',
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
		if ( 'room-locations' === get_post_type() ) {
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
			$query->set( 'post_type', array( 'post', 'page', 'room-locations' ) );
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
					'room-locations'
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

		if ( get_post_type() == 'room-locations' ) {
			if ( is_single() ) {
				// checks if the file exists in the theme first,
				// otherwise serve the file from the plugin
				if ( $theme_file = locate_template( array ( 'single-room-locations.php' ) ) ) {
					$template_path = $theme_file;
				} else {
					$template_path = trailingslashit( dirname( plugin_dir_path( __FILE__ ) ) ). 'single-room-locations.php';
				}
			}
			elseif ( is_archive() ) {
				if ( $theme_file = locate_template( array ( 'archive-room-locations.php' ) ) ) {
					$template_path = $theme_file;
				} else {
					$template_path = trailingslashit( dirname( plugin_dir_path( __FILE__ ) ) ) . 'archive-room-locations.php';
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

			if ( 'room-locations' == get_post_type() ) {
				$truncate = get_post_meta($post->ID, 'meta_room_location_desc', true);
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


	/*
	 * TODO: remove this function.
	 */
	public static function publish_room_location( $room_location ) {

		$post_id = wp_insert_post(
			array(
				'post_title' => $room_location['title'],
				'post_content' => $room_location['description'],
				'post_type' => 'room-locations',
				'post_status' => 'publish'
			));



	}


	public static function get_room_locations( $room_post_ID ) {

		$room_locations = array();

		$location_IDs = get_post_meta($room_post_ID, 'meta_room_locations_ids', true);

		if( isset($location_IDs ) && !empty($location_IDs ) ) {


			foreach ( $location_IDs as $location_ID ) {
				$room_location = self::get_room_location( $location_ID );
				if ( $room_location != false ) {
					// Make sure room_id of all room locations for this room are always set to the room's post id.
					$room_location['room_id'] = $room_post_ID;
					$room_locations[] = $room_location;
				} else {
					error_log( __( 'No room location for location ID = ' . $location_ID, GUESTABA_HSP_TEXTDOMAIN ) );
				}
			}

		}

		return $room_locations;

	}

	public static function get_next_available_room_location( $room_id, $start_time, $duration ) {

		$room_locations = self::get_available_room_locations( $room_id, $start_time, $duration );
		$n = count( $room_locations );
		$idx = mt_rand( 0, $n - 1);
		return $room_locations[ $idx ];

	}
	
    /*
     * Verify that necesary fields are set and the the location_id/room_number combination 
     * is unique. 
     */
	public static function validate_room_location( $room_location_id, $location_id, $unit_number ) {
        
        $validation = array(
            'isValid' => true,
            'message' => ''
        );
        $room_locations = self::get_room_locations_at_location( $location_id );
        
        if ( empty( $unit_number )) {
            $validation['isValid'] = false;
            $validation['message'] = __('Room number cannot be blank.', GUESTABA_HSP_TEXTDOMAIN);
            return $validation;
        }
        
        if ( count( $room_locations ) == 0 ) {
            // No room locations with that location ID.
           $validation['message'] = __( 'There are no room locations with specified location_id.', GUESTABA_HSP_TEXTDOMAIN);
        }
		else {
            foreach ( $room_locations as $room_location ) {
                if ( $room_location['location_id'] == $location_id  && $room_location['unit_number']  == $unit_number ) {
                    if ( $room_location['id'] == $room_location_id ) {
                        // Is valid since this is the room location with the location_id/room_number combo entered.
                    }
                    else {
                        $validation['isValid'] = false;
                        $validation['message'] = __('That room number already exists for this location.', GUESTABA_HSP_TEXTDOMAIN);
                    }
                }
                
            }
        }
        
        return $validation;
	}

    public static function get_room_locations_at_location( $location_id ) {

        $args = array(
            'post_type'      => 'room-locations',
            'post_status'    => 'publish',
            'posts_per_page' => - 1,
            'meta_query'	 => array(
                array( 'key' => 'location_id',
                    'value' => $location_id,
                    'type' => 'numeric',
                    'compare' => '=')
            ),
        );

        $rm_query = new WP_Query( $args );

        $room_locations = array();
        if ( $rm_query->have_posts() ) {
            while ( $rm_query->have_posts() ) : $rm_query->the_post();

                $room_locations[] = self::get_room_location( get_the_ID());

            endwhile;
        }

        wp_reset_postdata();

        return $room_locations;
    }

   

	public static function add_room_location( $room_location ) {

		$post_id = wp_insert_post(
			array(
				'post_title' => $room_location['location_title'] . ' ' . $room_location['unit_number'] . ' ' . $room_location['room_title'],
				'post_content' => $room_location['description'],
				'post_type' => 'room-locations',
				'post_status' => 'publish'
			));


		if ( $post_id !== false ) {


			if ( update_post_meta( $post_id, 'location_id', $room_location['location_id'] ) &&
			     update_post_meta( $post_id, 'unit_number', $room_location['unit_number'] ) &&
			     update_post_meta( $post_id, 'status', $room_location['status']) &&
				 update_post_meta( $post_id, 'room_id', $room_location['room_id'] ) &&
                 update_post_meta( $post_id, 'room_title',  $room_location['room_title'] ) )
			{
				return self::get_room_location( $post_id );

			}
			else {
				error_log( __FILE__  . ',' . __LINE__ . ':'.  __('Could add room location. Update post meta failed', GUESTABA_HSP_TEXTDOMAIN));
				return false;

			}
		}

		error_log( __FILE__  . ',' . __LINE__ . ':'.  __('Could add room location. Could not insert new post.', GUESTABA_HSP_TEXTDOMAIN));


		return false;

	}

	public static function modify_room_location( $room_location ) {

		$check_room_location = self::get_room_location( $room_location['id'] );

		if ( $check_room_location !== false ) {

			$post_id = $room_location['id'];

			if ( update_post_meta( $post_id, 'location_id', $room_location['location_id'] ) &&
			     update_post_meta( $post_id, 'unit_number', $room_location['unit_number'] ) &&
			     update_post_meta( $post_id, 'status', $room_location['status'] ) &&
				 update_post_meta( $post_id, 'room_id', $room_location['room_id'] ) &&
                 update_post_meta( $post_id, 'room_title', self::get_room_title( $room_location['room_id'] ))
			) {
				return self::get_room_location( $post_id );

			}
			else {
				error_log( __FILE__  . ',' . __LINE__ . ':'.  __('Could update room location, post ID = ' . $room_location['id'], GUESTABA_HSP_TEXTDOMAIN));

				return false;
			}
		}

		error_log(  __FILE__  .',' . __LINE__ . ':'. __('Room location does not exist, post ID = ' . $room_location['id'], GUESTABA_HSP_TEXTDOMAIN));

		return false;


	}

	public static function delete_room_location( $room_location_ID ) {

		$check_room_location = self::get_room_location( $room_location_ID );

		if ( $check_room_location !== false ) {

            if ( count( Reservations_Post_Type::get_reservations( $room_location_ID )) > 0 ) {
                throw new Exception(__('A room location with reservations cannot be deleted.', GUESTABA_HSP_TEXTDOMAIN));
            }

            // Make sure room location does not have any reservations. If it does, throw and exception.


			if ( wp_delete_post( $room_location_ID ) ) {
				return true;
			}
			else {
				error_log( __FILE__  . ',' . __LINE__ . ':'.  __('Could not delete room location, post ID = ' . $room_location_ID, GUESTABA_HSP_TEXTDOMAIN));
                throw new Exception(__('Could not delete room location, room location ID =' . $room_location_ID , GUESTABA_HSP_TEXTDOMAIN));

			}
		}
		else {
			error_log(  __FILE__  . ',' . __LINE__ . ':'. __('Room location does not exist, post ID = ' . $room_location_ID , GUESTABA_HSP_TEXTDOMAIN));
		    throw new Exception(__('Room location does not exist, post ID = ' . $room_location_ID , GUESTABA_HSP_TEXTDOMAIN));
        }
        // We should not end up here. TODO: Could be a good place for an assertion.

	}




	public static function get_room_location( $post_id ) {

		$post = get_post( $post_id );

		if ( $post != null ) {
			$room_location = self::get_array( $post );
			return $room_location;
		}
		else {
			return false;
		}
	}
	
	

	public static function get_array( $post ) {


		$room_location = array(
			'id' => $post->ID,
			'title' => $post->post_title,
			'description' => $post->post_content,
			'date' => $post->post_date,
			'timestamp' => get_post_time('U', true, $post),
			'name' => get_post_meta( $post->ID, 'name', true),
			'location_id' => get_post_meta( $post->ID, 'location_id', true),
			'unit_number' => get_post_meta( $post->ID, 'unit_number', true),
			'status' => get_post_meta( $post->ID, 'status', true),
			'room_id' => get_post_meta( $post->ID, 'room_id', true),
            'room_title' => get_post_meta( $post->ID, 'room_title', true)

		);
		/*
		* TODO: This returns a permalink but also dumps and error in debug mode.
		*  'permalink' => get_post_permalink( $post->ID )
		*/


		return $room_location;
	}

	public static function get_available_room_locations ( $room_id, $start_time, $duration ) {

		$room_locations = self::get_room_locations( $room_id);
		$available_room_locations = array();
		foreach ( $room_locations as $room_location ) {
			$resevations = Reservations_Post_Type::get_reservations( $room_location['id']);
			if ( count( $resevations) == 0) {
				$available_room_locations[] = $room_location;
				continue;
			}
			if ( ! Reservation_Agent::check_reservation_overlap( $resevations, $start_time, $duration )) {
				$available_room_locations[] = $room_location;
				continue;
			}
		}

		return $available_room_locations;


	}
	
	public static function room_location_is_available( $room_location_id, $start_time, $duration ) {
		
		$reservations = Reservations_Post_Type::get_reservations( $room_location_id );
		
		$ret_val = false;
		if ( count( $reservations ) == 0) {
			$ret_val = true;
		} 
		else {
			// Return true if there is overlap. False from this call means room is available. 
			$ret_val = ! Reservation_Agent::check_reservation_overlap( $reservations, $start_time, $duration );
		}
		
		return $ret_val;
	}

	public static function get_room_addresses( $room_id ) {

		$room_locations = self::get_room_locations( $room_id );
		$facility_location_ids = array();
		foreach ( $room_locations as $room_location ) {
			$facility_location_ids[] = $room_location['location_id'];
		}

		$facility_location_ids = array_unique( $facility_location_ids );
		
		$room_addresses = array();
		foreach ( $facility_location_ids as $facility_location_id ) {
			$room_addresses[] = Locations_Post_Type::get_location( $facility_location_id) ;
		}
		
		return $room_addresses;

	}

	public static function get_room_map_center( $room_id ) {
		$room_addresses = self::get_room_addresses( $room_id );
        if ( isset( $room_addresses[0]) ) {
            $center_address = $room_addresses[0];
            $map_center = $center_address['address1'] . ',' . $center_address['city'] . ',' . $center_address['state'] . ',' . $center_address['post_code'];
        }
        else {
            // TODO: add define address.
            // This prevents error when room location has not yet been assigned to a location.
            $map_center = '';
        }
        return $map_center;
	}

    private static function get_room_title( $room_id ) {

        /* Add room title  */
        $room = Rooms_Post_Type::get_room( $room_id );
        if ( !isset( $room )  || empty( $room )) {
            throw new Exception(__("Could not retrieve room for room location", GUESTABA_HSP_TEXTDOMAIN));
        }

        return $room['title'];

    }

}
