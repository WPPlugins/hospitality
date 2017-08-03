<?php

/**
 *
 */
class Locations_Post_Type {
	/**
	 *  String to define post type name.
	 *  @since	1.0.0
	 *  @access	protected
	 *  @var String  $post_type  Stores post_type name
	 */
	protected $post_type ;

	/**
	 * Array for storing UI labels for Locations custom post type
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var     array    $labels   Stores UI labels for Location CPT
	 */
	protected $labels;

	/**
	 * Array for storing argument passed to register_post_type
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var     array    $args   Stores UI labels for Location CPT
	 */
	protected $args;

	/**
	 * Constructor for Location Post Type
	 * Initializes labels and args for registration.
	 * @since    1.0.0
	 */

	public function __construct() {

		$this->post_type = 'locations';

		$theme = wp_get_theme();
		$text_domain = $theme->get('TextDomain');

		$this->labels = array(
			'name'                => __( 'Location Listings', $text_domain ),
			'singular_name'       => __( 'Location', $text_domain ),
			'menu_name'           => __( 'Locations', $text_domain ),
			'parent_item_colon'   => __( 'Parent Location:', $text_domain ),
			'all_items'           => __( 'Locations', $text_domain ),
			'view_item'           => __( 'View Location', $text_domain ),
			'add_new_item'        => __( 'Add New Location', $text_domain ),
			'add_new'             => __( 'Add New', $text_domain ),
			'edit_item'           => __( 'Edit Location', $text_domain ),
			'update_item'         => __( 'Update Location', $text_domain ),
			'search_items'        => __( 'Search Locations', $text_domain ),
			'not_found'           => __( 'No locations found', $text_domain ),
			'not_found_in_trash'  => __( 'No locations found in Trash', $text_domain )
		);

		$this->args = array(
			'label'               => __( 'Locations', $text_domain ),
			'labels'              => $this->labels,
			'description'         => __('Location description goes here.', GUESTABA_HSP_TEXTDOMAIN ),
			'supports'            => array( 'title', 'excerpt', 'author', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', 'thumbnail' ),
			'hierarchical'        => true,
			'public'              => true,

			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=rooms',
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,

			'query_var'           => true,
			'publicly_queryable'  => true,

			'exclude_from_search' => false,
			'has_archive'         => true,

			'can_export'          => true,
			'menu_position'       => 4,
			'rewrite'             => array(
				'slug'            => 'locations',
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
		if ( 'locations' === get_post_type() ) {
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
			$query->set( 'post_type', array( 'post', 'page', 'locations' ) );
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
					'locations'
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

		if ( get_post_type() == 'locations' ) {
			if ( is_single() ) {
				// checks if the file exists in the theme first,
				// otherwise serve the file from the plugin
				if ( $theme_file = locate_template( array ( 'single-locations.php' ) ) ) {
					$template_path = $theme_file;
				} else {
					$template_path = trailingslashit( dirname( plugin_dir_path( __FILE__ ) ) ). 'single-locations.php';
				}
			}
			elseif ( is_archive() ) {
				if ( $theme_file = locate_template( array ( 'archive-locations.php' ) ) ) {
					$template_path = $theme_file;
				} else {
					$template_path = trailingslashit( dirname( plugin_dir_path( __FILE__ ) ) ) . 'archive-locations.php';
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

			if ( 'locations' == get_post_type() ) {
				$truncate = get_post_meta($post->ID, 'meta_location_desc', true);
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



	public static function add_default_location() {

		 if ( count( self::get_locations() ) === 0 ) {
			$location = array(
				'title' => __('Default location', GUESTABA_HSP_TEXTDOMAIN),
				'description' => __('This is the default location for your facility.', GUESTABA_HSP_TEXTDOMAIN),
				'address1' => __('123 Your Street', GUESTABA_HSP_TEXTDOMAIN),
				'address2' => '',
				'city' =>  __('Your City', GUESTABA_HSP_TEXTDOMAIN),
				'state' =>  __('Your State', GUESTABA_HSP_TEXTDOMAIN),
				'postal_code' =>  __('Postal Code/Zip', GUESTABA_HSP_TEXTDOMAIN),
			);
			self::add_location( $location );
		}

	}

	public static function add_location( $location ) {

		$post_id = wp_insert_post(
			array(
				'post_title' => $location['title'],
				'post_content' => $location['description'],
				'post_type' => 'locations',
				'post_status' => 'publish'
			));


		if ( $post_id !== false ) {

			if ( update_post_meta( $post_id, 'address1', $location['address1'] ) &&
			     update_post_meta( $post_id, 'address2', $location['address2'] ) &&
			     update_post_meta( $post_id, 'city', $location['city'] ) &&
			     update_post_meta( $post_id, 'state', $location['state'] &&
			     update_post_meta( $post_id, 'postal_code', $location['postal_code']))
			) {
				return self::get_location( $post_id );

			}
			else {
				error_log( __FILE__  . ',' . __LINE__ . ':'.  __('Could add location. Update post meta failed', GUESTABA_HSP_TEXTDOMAIN));
				return false;

			}
		}

		error_log( __FILE__  . ',' . __LINE__ . ':'.  __('Could add location. Could not insert new post.', GUESTABA_HSP_TEXTDOMAIN));


		return false;

	}

	public static function modify_location( $location ) {

		$check_location = self::get_location( $location['id'] );

		if ( $check_location !== false ) {

			$post_id = $location['id'];

			if ( update_post_meta( $post_id, 'address1', $location['address1'] ) &&
			     update_post_meta( $post_id, 'address2', $location['address2'] ) &&
			     update_post_meta( $post_id, 'city', $location['city'] ) &&
			     update_post_meta( $post_id, 'state', $location['state'] &&
                 update_post_meta( $post_id, 'postal_code', $location['postal_code']))
			) {
				return self::get_location( $post_id );

			}
			else {
				error_log( __FILE__  . ',' . __LINE__ . ':'.  __('Could update location, post ID = ' . $location['id'], GUESTABA_HSP_TEXTDOMAIN));
				return false;
			}
		}

		error_log(  __FILE__  .',' . __LINE__ . ':'. __('Location does not exist, post ID = ' . $location['id'], GUESTABA_HSP_TEXTDOMAIN));

		return false;


	}

	public static function delete_location( $location_ID ) {

		$check_location = self::get_location( $location_ID );

		if ( $check_location !== false ) {

			$post_id = $location_ID;

			if ( wp_delete_post( $post_id ) ) {
				return true;
			}
			else {
				error_log( __FILE__  . ',' . __LINE__ . ':'.  __('Could not delete location, post ID = ' . $location_ID, GUESTABA_HSP_TEXTDOMAIN));
				return false;

			}
		}
		else {
			error_log(  __FILE__  . ',' . __LINE__ . ':'. __('Location does not exist, post ID = ' . $location_ID, GUESTABA_HSP_TEXTDOMAIN));
		}

		return false;


	}
	public static function get_locations() {

		$args = array(
			'post_type'      => 'locations',
			'post_status'    => 'publish',
			'posts_per_page' => - 1
		);

		$post_query = new WP_Query( $args );

		$locations = array();
		while ( $post_query->have_posts() ) : $post_query->the_post();
			$location = self::get_location( get_the_ID() );
			$locations[] = $location;

		endwhile;

		wp_reset_postdata();

		return $locations;

	}

	public static function get_location( $post_id ) {

		$post = get_post( $post_id );

		if ( $post != null ) {
			$location = self::get_array( $post );
			return $location;
		}
		else {
			return false;
		}
	}


	public static function get_array( $post ) {


		$location = array(
			'id' => $post->ID,
			'title' => $post->post_title,
			'description' => $post->post_content,
			'date' => $post->post_date,
			'timestamp' => get_post_time('U', true, $post),
			'address1' => get_post_meta( $post->ID, 'address1', true),
			'address2' => get_post_meta( $post->ID, 'address2', true),
			'city' => get_post_meta( $post->ID, 'city', true),
			'state' => get_post_meta( $post->ID, 'state', true),
			'post_code' => get_post_meta( $post->ID, 'postal_code', true)
		);

		return $location;
	}

	
}


