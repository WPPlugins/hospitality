<?php


/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Hospitality
 * @subpackage Hospitality/includes
 * @author     Wes Kempfer <wkempferjr@tnotw.com>
 */
class Hospitality_Activator {

	/**
	 * Runs at activation time. It currently adds settings defaults via
	 * and instance of the Hospitality_Settings class. 
	 *
	 * @since    1.0.0
	 */
	public static function activate() {	
		if ( current_user_can( 'activate_plugins') ) {	
			$settings = new Hospitality_Settings();
			$settings->add_option_defaults();

            $hsp = new Hospitality();
            $hsp->create_default_objects() ;
		}
	}

}
