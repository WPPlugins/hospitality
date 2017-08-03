<?php
/**
 * Class: Hospitality Page Manager
 *
 * This class creates pages that are used to for the default display of Hospitality objects
 */

class Hospitality_Page_Manager {

	/**
	 *
	 * Called from plugins_loaded hook. Registered in Hospitality class. Creates the page
	 * used to display room lists.
	 */
	public function create_rooms_listing_page() {

		if ( current_user_can( 'manage_options' ) && current_user_can( 'edit_posts' ) ) {
			// See if pages exists. If not, create it.
			if ( get_page_by_path( GUESTABA_ROOMS_LISTING_PAGE_NAME ) == null ) {
				global $user_ID;

				$page = array();

				$page['post_type']    = 'page';
				$page['post_content'] = '';
				$page['post_parent']  = 0;
				$page['post_author']  = $user_ID;
				$page['post_status']  = 'publish';
				$page['post_title']   = 'Rooms Listing';
				// $page = apply_filters('yourplugin_add_new_page', $page, 'teams');
				$pageid = wp_insert_post( $page );
				if ( $pageid == 0 ) { /* Add Page Failed */
					error_log('Room listing pageid is 0 in file ' . __FILE__ . ', line ' . __LINE__ . '.');

				}
			}
		}
	}
	/**
	 *
	 * Called from plugins_loaded hook. Registered in Hospitality class. Creates the page
	 * used to display room detail.
	 */
	public function create_room_detail_page() {

		if ( current_user_can( 'manage_options' ) && current_user_can( 'edit_posts' ) ) {
			// See if pages exists. If not, create it.
			if ( get_page_by_path( GUESTABA_ROOM_DETAIL_PAGE_NAME ) == null ) {
				global $user_ID;

				$page = array();

				$page['post_type']    = 'page';
				$page['post_content'] = '';
				$page['post_parent']  = 0;
				$page['post_author']  = $user_ID;
				$page['post_status']  = 'publish';
				$page['post_title']   = 'Room Detail';
				$pageid = wp_insert_post( $page );
				if ( $pageid == 0 ) { /* Add Page Failed */
					error_log('Room detail pageid is 0 in file ' . __FILE__ . ', line ' . __LINE__ . '.' );
				}
			}
		}
	}


	/**
	 *
	 * Registered as a 'the_content' filter. Returns the room listing shortcode output
	 * if the page is the rooms listing page.
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function display_rooms_list_page( $content ) {
		global $post;
		if ( $post->post_name == GUESTABA_ROOMS_LISTING_PAGE_NAME ) {
			return do_shortcode('[room_listing]');
		}
		else {
			return $content;
		}

	}


	/**
	 * Registered as a 'the_content' filter. Returns the room detail shortcode output
	 * if the page is a rooms detail page.
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function display_room_detail_page( $content ) {

		global $post;
		global $wp_query ;


		if ( $post->post_name == GUESTABA_ROOM_DETAIL_PAGE_NAME ) {
			if(isset($wp_query->query_vars[ GUESTABA_ROOM_DETAIL_ID_VAR ])) {
				$rooms_post_id = urldecode( $wp_query->query_vars[ GUESTABA_ROOM_DETAIL_ID_VAR ] );
			}
			else if(isset($wp_query->query_vars[ GUESTABA_ROOM_DETAIL_NAME_VAR ])) {
				$rooms_post_name = urldecode( $wp_query->query_vars[ GUESTABA_ROOM_DETAIL_NAME_VAR ] );
				$wp_post = get_page_by_path( $rooms_post_name,  OBJECT, 'rooms' ) ;

				if ( $wp_post != null ) {
					$rooms_post_id = $wp_post->ID ;
				}
			}

			if ( isset( $rooms_post_id )) {
				return do_shortcode( '[room_detail id="' . $rooms_post_id . '" ]' );
			}
		}

		return $content;

	}


	/**
	 * Registered as add_query_vars callback in the Hospitality class. It enables passing
	 * a rooms post ID as a query parameter.
	 *
	 * @param $the_vars
	 *
	 * @return array
	 */
	public function add_query_vars( $the_vars ) {
		$the_vars[] = GUESTABA_ROOM_DETAIL_ID_VAR ;
		return $the_vars;
	}

	/**
	 * Registered as add_rewrite_rules callback in the Hospitality class. It adds
	 * a rewrite rule that maps rooms-listing/room-slug to a parameterized URL.
	 *
	 * @param $the_vars
	 *
	 * @return array
	 */

	public function add_rewrite_rules () {

		// get page ID for room detail page
		$wp_post = get_page_by_path( GUESTABA_ROOM_DETAIL_PAGE_NAME,  OBJECT, 'page' ) ;
		if ( $wp_post != null ) {
			$detail_page_id = $wp_post->ID ;
		}
		else {
			error_log('wp_post is null, file '. __FILE__ . ', line ' .  __LINE__ );
		}

		add_rewrite_rule('^' . GUESTABA_ROOM_DETAIL_PAGE_NAME . '/([^/]*)/?',  'index.php?page_id=' . $detail_page_id . '&' . GUESTABA_ROOM_DETAIL_NAME_VAR . '=$matches[1]','top');

	}

	/**
	 * Adds rewrite tag to support rule added in add_rewrite_rules function.
	 */
	public function add_rewrite_tags() {
		add_rewrite_tag('%' . GUESTABA_ROOM_DETAIL_NAME_VAR . '%', '/([^/]*)/?');
	}


}