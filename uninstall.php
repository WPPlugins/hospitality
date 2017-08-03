<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Hospitality
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}


$option = get_option('guestaba_hsp_settings');

if ( $option['hsp_remove_data_on_uninstall'] ) {
    if ( current_user_can('delete_plugins') )  {
    
			// Remove post types
			$rooms_posts = get_posts( array( 'post_type' => 'rooms' ) );
		
			if ( !empty( $rooms_posts ) ) {
	   			foreach( $rooms_posts as $a_post ) {     		
	     			wp_delete_post( $a_post->ID, true);  		
	   			}
			}
			
			$amenity_sets_posts = get_posts( array( 'post_type' => 'amenity-sets' ) );
		
			if ( !empty( $amenity_sets_posts ) ) {
	   			foreach( $amenity_sets_posts as $a_post ) {     		
	     			wp_delete_post( $a_post->ID, true);  		
	   			}
			}
			
			$pricing_models_posts = get_posts( array( 'post_type' => 'pricing-models' ) );
		
			if ( !empty( $pricing_models_posts ) ) {
	   			foreach( $pricing_models_posts as $a_post ) {     		
	     			wp_delete_post( $a_post->ID, true);  		
	   			}
			}
			
			// Remove options
			delete_option('guestaba_hsp_settings');
			delete_option( GUESTABA_HOSPITALITY_VERSION_KEY);
    }

}
		

