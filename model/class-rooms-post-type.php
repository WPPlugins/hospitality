<?php

/**
 * Encapsulates attributes and behavior of the room post type.
 *
 * @link       http://guestaba.com
 * @since      1.0.0
 *
 * @package    Hospitality
 * @subpackage Hospitality/model
 */

/**
 * Room Post Type class
 *
 * Defines attribues and behavior of the Room post type
 *
 *
 * @since      1.0.0
 * @package    Hospitality
 * @subpackage Hospitality/model
 * @author     Wes Kempfer <wkempferjr@tnotw.com>
 */
class Rooms_Post_Type {
	
	/**
	 *  String to define post type name.
	 *  @since	1.0.0
	 *  @access	protected
	 *  @var String  $post_type  Stores post_type name
	 */
	protected $post_type ;
	
	/**
	 * Array for storing UI labels for Rooms custom post type
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var     array    $labels   Stores UI labels for Room CPT
	 */
	protected $labels;
	
	/**
	 * Array for storing argument passed to register_post_type
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var     array    $args   Stores UI labels for Room CPT
	 */
	protected $args;
	
	/**
	 * Constructor for Room Post Type
	 * Initializes labels and args for registration.
	 * @since    1.0.0
	 */
	
	public function __construct() {
		
		$this->post_type = 'rooms';
		
		$theme = wp_get_theme();
		$text_domain = $theme->get('TextDomain');

		$this->labels = array(
		    'name'                => __( 'Room Listings', $text_domain ),
            'singular_name'       => __( 'Room', $text_domain ),
            'menu_name'           => __( 'Rooms', $text_domain ),
            'parent_item_colon'   => __( 'Parent Room:', $text_domain ),
            'all_items'           => __( 'All Rooms', $text_domain ),
            'view_item'           => __( 'View Room', $text_domain ),
            'add_new_item'        => __( 'Add New Room', $text_domain ),
            'add_new'             => __( 'Add New', $text_domain ),
            'edit_item'           => __( 'Edit Room', $text_domain ),
            'update_item'         => __( 'Update Room', $text_domain ),
            'search_items'        => __( 'Search Rooms', $text_domain ),
            'not_found'           => __( 'No rooms found', $text_domain ),
            'not_found_in_trash'  => __( 'No rooms found in Trash', $text_domain )
		);
		
		$this->args = array(
		     'label'               => __( 'Rooms', $text_domain ),
            'labels'              => $this->labels,
            'description'         => __('Room description goes here.', GUESTABA_HSP_TEXTDOMAIN ),
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
                'slug'            => 'rooms',
                'with_front'      => true,
                'pages'           => true,
                'feeds'           => true,
            ),
            'capability_type'     => 'post',
            'taxonomies'          => array( 'category', 'post_tag' ),
		     'show_in_rest'       => true
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
		if ( 'rooms' === get_post_type() ) {
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
			$query->set( 'post_type', array( 'post', 'page', 'rooms' ) );
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
	                    'rooms'
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
  
	    if ( get_post_type() == 'rooms' ) {
	        if ( is_single() ) {
	            // checks if the file exists in the theme first,
	            // otherwise serve the file from the plugin
	            if ( $theme_file = locate_template( array ( 'single-rooms.php' ) ) ) {
	                $template_path = $theme_file;
	            } else {
	                $template_path = trailingslashit( dirname( plugin_dir_path( __FILE__ ) ) ). 'single-rooms.php';
	            }	         	
	        }
	    	elseif ( is_archive() ) {
	            if ( $theme_file = locate_template( array ( 'archive-rooms.php' ) ) ) {
	                $template_path = $theme_file;
	            } else { 
	            	$template_path = trailingslashit( dirname( plugin_dir_path( __FILE__ ) ) ) . 'archive-rooms.php';
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
	
	        if ( 'rooms' == get_post_type() ) {
	            $truncate = get_post_meta($post->ID, 'meta_room_desc', true);
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

	public static function get_rooms( $criteria ) {

		$amenities_criteria  = $criteria['amenities'];

		if ( empty( $amenities_criteria[0] ) )  {
			array_shift( $amenities_criteria );
		}
		$occupants = $criteria['occupants'];
		$max_price = $criteria['max_price'];



		$args = array(
			'post_type'      => 'rooms',
			'post_status'    => 'publish',
			'meta_query'	 => array(
				array( 'key' => 'meta_room_max_occupancy',
					    'value' => $occupants,
					 	'type' => 'numeric',
						'compare' => '>=')
			),
			'posts_per_page' => - 1
		);

		$rm_query = new WP_Query( $args );

		$rooms = array();
		if ( $rm_query->have_posts() ) {
			while ( $rm_query->have_posts() ) : $rm_query->the_post();


				$room_id = get_the_ID();
				$room_amenities = self::get_amenity_titles( $room_id ) ;

				$reservation_pricing = self::get_reservation_pricing( $room_id, $criteria['start_time'], $criteria['duration']);


				// check for price
				if ( $max_price == 0 || $reservation_pricing['per_day'] <= $max_price )  {
					// check for amenities
					if ( count($amenities_criteria) == 0 || array_intersect( $amenities_criteria, $room_amenities ) == $amenities_criteria ) {
						// meets price and amenity criteria
						$room = self::get_room( get_the_ID());
						$room['reservation_pricing'] = $reservation_pricing;
						$rooms[] = $room;
					}

				}

			endwhile;
		}

		wp_reset_postdata();

		return $rooms;

	}	
	
	public static function get_all_rooms() {
		
		$args = array(
			'post_type'      => 'rooms',
			'post_status'    => 'publish',
			'posts_per_page' => - 1
		);

		$rm_query = new WP_Query( $args );

		$rooms = array();
		if ( $rm_query->have_posts() ) {
			while ( $rm_query->have_posts() ) : $rm_query->the_post();

				$rooms[] = self::get_room( get_the_ID());

			endwhile;
		}

		wp_reset_postdata();

		return $rooms;
	}

	

	public static function get_room( $post_id ) {

		$post = get_post( $post_id );

		if ( $post != null ) {
			$room = self::get_array( $post );
			return $room;
		}
		else {
			return false;
		}
	}

	public static function get_array( $post ) {

		if ( has_post_thumbnail( $post->ID ) ) {
			$post_thumbnail_id = get_post_thumbnail_id($post->ID);
			$post_thumbnail_url = wp_get_attachment_url($post_thumbnail_id);
		}
		else {
			$post_thumbnail_url = "";

		}


		$room = array(
			'id' => $post->ID,
			'title' => $post->post_title,
			'description' => $post->post_content,
			'date' => $post->post_date,
			'timestamp' => get_post_time('U', true, $post),
			'room_slogan' => get_post_meta( $post->ID, 'meta_room_slogan', true),
			'room_desc' => get_post_meta( $post->ID, 'meta_room_desc', true),
			'room_excerpt' => get_post_meta( $post->ID, 'meta_room_excerpt', true),
			'max_occupancy' => get_post_meta( $post->ID, 'meta_occupancy', true),
			'pricings' => self::get_pricings( $post->ID ),
			'current_price' => do_shortcode( '[current_price id="' . $post->ID . '"]' ),
			'pricings_html' => do_shortcode( '[pricings pricing_heading_tag="h2" id="' . $post->ID . '"]' ),
			'price_range' => self::get_price_range( $post->ID ),
			'amenities' => self::get_amenity_list( $post->ID ),
			'amenity_icons' => self::get_amemity_icons( $post->ID ),
			'amenity_list_icon' => self::get_amenity_list_icon( $post->ID ),
			'slider_images' => get_post_meta( $post->ID, 'meta_room_slider', true ),
			'thumbnail_url' => $post_thumbnail_url,
			'post_url' => get_permalink( $post ),
			'room_location_ids' => get_post_meta( $post->ID, 'meta_room_locations_ids', true),
			'room_addresses' => Room_Locations_Post_Type::get_room_addresses( $post->ID ),
			'map_center' => Room_Locations_Post_Type::get_room_map_center( $post->ID ),
			'slider_config' => self::get_slider_config( $post->ID ),
			'location_html' => do_shortcode( '[location_map id="' . $post->ID . '"]' )

		);

		return $room;
	}
	
	/*
	 * TODO: change icon in settings to accept any font awsome icon name. For now, the two allowable settings
	 * are checkbox and arrow. Convert them to fa icon names.
	 */

	public static function get_amenity_list_icon( $post_id ) {
		$icon_setting = get_post_meta( $post_id, 'meta_room_amenity_list_icon', true );
		
		switch ( $icon_setting) {
			case 'arrow':
				$icon = 'arrow-right';
				break;
			case 'checkmark':
			default:
				$icon = 'check';
				break;
		}
		
		return $icon;
	}
	
	public static function get_slider_config( $post_id ) {
		
		$slider_config = array();

		$meta = get_post_meta( $post_id );
		if ( $meta === false) {
			throw new Exception(__('Could not retrieve room slider configuration.', GUESTABA_HSP_TEXTDOMAIN));
		}

		$slider_config['post_id'] = $post_id;

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
	

	public static function get_price_range( $post_id ) {
		
		$default_price_range = __('Call', GUESTABA_HSP_TEXTDOMAIN );
		
		$options = get_option(GUESTABA_HSP_OPTIONS_NAME);

		$pricing_model_post_ID = get_post_meta( $post_id, 'meta_room_pricing_select', true );
		$room_pricings         = get_post_meta( $pricing_model_post_ID, 'meta_pricing_model_list', true );

		if ( ! empty( $room_pricings ) ) {
			$output_pricing = array();
			foreach ( $room_pricings as $room_pricing ) {

				$output_pricing[] = min( $room_pricing['dow_price'] );
				$output_pricing[] = max( $room_pricing['dow_price'] );

			}
			$room_price_range = $options['hsp_currency_symbol'] . min( $output_pricing ) . ' - ' . max( $output_pricing );

		} else {
			$room_price_range = $default_price_range;
		}

		$output = $room_price_range;


		return $output;

	}

	

	public static function get_current_price( $post_id, $timestamp = 0 ) {


		/** To retrieve room pricings, get pricing model post ID, then get pricings from pricing model post */
		$pricing_model_post_ID = get_post_meta( $post_id, 'meta_room_pricing_select', true );
		$room_pricings         = get_post_meta( $pricing_model_post_ID, 'meta_pricing_model_list', true );


		// If no price specified, return 0.
		if ( $room_pricings == false ) {
			return 0;
		}

		// current time
		if ( $timestamp == 0 ) {
			$timestamp = time();
		}


		$year = date('Y', $timestamp);
		$dow  = strtolower( date( 'l', $timestamp ) );

		$current_price = 0;
		foreach ( $room_pricings as $room_pricing ) {
			$start_date = $room_pricing['meta_room_pricing_date01']['date_start'] . ', ' . $year;
			$end_date   = $room_pricing['meta_room_pricing_date01']['date_end'] . ', ' . $year;

			$start_lt = strtotime( $start_date );
			$end_lt   = strtotime( $end_date );

			// This happens when a pricing term spans the new year.
			if ( $start_lt > $end_lt ) {
				$year++;
				$end_date   = $room_pricing['meta_room_pricing_date01']['date_end'] . ', ' . $year;
				$end_lt   = strtotime( $end_date );
			}

			if ( $timestamp >= $start_lt && $timestamp <= $end_lt ) {
				$current_price = $room_pricing['dow_price'][ $dow ];
			}
		}


		return $current_price;
	}

	public static function get_reservation_pricing( $room_id, $start_time, $duration ) {

		// get month, day, year for start time
		// convert to seconds
		$p_start_time = $start_time / 1000;


		$days = $duration / 86400000;

		$room_amount = 0;

		for ( $i = 0 ; $i < $days ; $i++ ) {
			$tstamp = $p_start_time + ( $i * 86400 );
			$room_amount += self::get_current_price( $room_id, $tstamp );

		}

		$room_amount = number_format( $room_amount, 2);
		$reservation_total = $room_amount ;
		
		// Add fees and taxes, if any
		$options = get_option( GUESTABA_HSP_OPTIONS_NAME);
		$fee = $options['hsp_fee_amount'];
		$tax = $options['hsp_tax_amount'];
		$fee_is_percentage =  $options['hsp_fee_is_percentage'];
		$tax_is_percentage =  $options['hsp_tax_is_percentage'];

		$fee_amount = 0;

		if ( $fee > 0 ) {
			if ( $fee_is_percentage ) {
				$fee_amount =  $reservation_total *  ( $fee / 100 );
			}
			else {
				$fee_amount = $fee;
			}
		}

		$fee_amount = number_format( $fee_amount, 2);

		$reservation_total += $fee_amount;

		$tax_amount = 0;

		if ( $tax > 0 ) {
			if ( $tax_is_percentage ) {
				$tax_amount =  $reservation_total * ( $tax / 100 );
			}
			else {
				$tax_amount = $tax;
			}
		}

		$tax_amount = number_format( $tax_amount, 2);

		$reservation_total += $tax_amount;


		$reservation_pricing = array(
			'fee_amount' => $fee_amount,
			'tax_amount' => $tax_amount,
			'room_amount' => $room_amount,
			'price' => $reservation_total,
			'per_day' => $room_amount / $days
		);

		return $reservation_pricing;
	}
	



	private static function get_amenity_list ( $post_id ) {

		$room_amenity_post_ID = get_post_meta( $post_id, 'meta_room_amenity_select', true );

		// $room_amenities = get_post_meta( $post_id, 'meta_room_amenity_list');
		$amenities_set_list = array();
		if ( ! empty( $room_amenity_post_ID ) ) {
			$amenities_set_list = get_post_meta( $room_amenity_post_ID, 'meta_amenity_set_list', true );
		}

		$room_specific_amenities = get_post_meta( $post_id, 'meta_room_amenity_list', true );
		if ( empty( $room_specific_amenities ) ) {
			$room_specific_amenities = array();
		}
		$room_amenities = array_merge( $amenities_set_list, $room_specific_amenities );

		return $room_amenities;
	}

	private static function get_amenity_titles ( $post_id ) {

		$amenity_list_titles = array();

		$amenity_list = self::get_amenity_list( $post_id ) ;

		foreach ( $amenity_list as $amenity ) {
			$amenity_list_titles[] = $amenity['title'];
		}

		return $amenity_list_titles;

	}

	private static function get_amemity_icons( $post_id ) {

		$amenity_list = self::get_amenity_list( $post_id );
		$icon_list = array();

		foreach ( $amenity_list as $amenity ) {
			if ( !empty( $amenity['icon'])) {
				$icon_list[] = array (
					'icon' => $amenity['icon'],
					'title' => $amenity['title']
				);
			}
		}

		return $icon_list;

	}


	private static function get_pricings( $post_id ) {
		/** To retrieve room pricings, get pricing model post ID, then get pricings from pricing model post */
		$pricing_model_post_ID = get_post_meta( $post_id, 'meta_room_pricing_select', true );
		$room_pricings         = get_post_meta( $pricing_model_post_ID, 'meta_pricing_model_list', true );
		return $room_pricings;

	}
}
?>