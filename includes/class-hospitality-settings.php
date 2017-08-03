<?php

/**
 * This class defines and maintains access to the plugin
 * settings.
 *
 * @link       http://guestaba.com
 * @since      1.0.0
 * @package    Hospitality
 * @subpackage Hospitality/includes
 * @author     Wes Kempfer <wkempferjr@tnotw.com>
 */
class Hospitality_Settings {


	/*
	 * Sets the name of plugin option.
	 */
	private $options_name = GUESTABA_HSP_OPTIONS_NAME;

	/*
	 * Default values for plugin options are defined here.
	 * These values are recorded in wp_option at activation time.
	 *
	 */
	private $default_excerpt_len = 200 ;
	private $default_use_widget_area = false;
	private $default_remove_data_on_uninstall = false;
	private $default_amenities_title ;
	private $default_currency_symbol = '$';

	private $default_checkout_time = '11:00am';
	private $default_checkin_time = '4:00pm';

	private $version = GUESTABA_HOSPITALITY_VERSION_NUM ;


	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->default_amenities_title = __( 'Amenities', GUESTABA_HSP_TEXTDOMAIN );

	}


	/*
	 * Get the plugin option name.
	 *
	 * @return string plugin option name.
	 */
	public function get_options_name() {
		return $this->options_name;
	}


	/*
	 * This function is called at activation time and by the constructor. It records
	 * the plugin settings default values in the wp_options table.
	 * If the plugin options already exist in the database, they
	 * are not overwritten.
	 *
	 * @since 1.0.0
	 */
	public function add_option_defaults() {

		if ( current_user_can('activate_plugins') ) {
			$options = array();
			$options['hsp_room_excerpt_len'] = $this->default_excerpt_len;
			$options['hsp_amenities_title'] = $this->default_amenities_title;
			$options['hsp_remove_data_on_uninstall'] = $this->default_remove_data_on_uninstall;
			$options['hsp_currency_symbol'] = $this->default_currency_symbol;
			$options['version'] = $this->version ;
			$options['hsp_street_address_1'] = '701 SW Sixth Avenue';
			$options['hsp_street_address_2'] = '';
			$options['hsp_city'] = 'Portland';
			$options['hsp_state'] = 'OR';
			$options['hsp_country'] = 'US';
			$options['hsp_postal_code'] = '97205';
			$options['hsp_google_maps_api_key'] = '';
			$options['hsp_google_maps_lat'] = 0;
			$options['hsp_google_maps_lng'] = 0;
			$options['hsp_google_maps_default_zoom'] = '';
			$options['hsp_checkin_time'] = $this->default_checkin_time;
			$options['hsp_checkout_time'] = $this->default_checkout_time ;
			$options['hsp_searchable_amenities'] = '';
			$options['hsp_payment_gateway_enable_offline'] = GUESTABA_HSP_OFF;
            $options['hsp_payment_gateway_offline_instructions'] = '';
            $options['hsp_confirmation_email_message'] = '';
            $options['hsp_reservation_entry_point_slug'] = 'room-search';
            $options['hsp_enable_user_controls'] = false;


			add_option( $this->options_name, $options );
		}

	}




	/*
	 * This function was intended to be called to delete the
	 * options from the database.
	 *
	 * @todo Can this delete_options() be removed.
	 * @since 1.0.0
	 */

	public function delete_options() {
		if ( current_user_can('delete_plugins') ) {
			delete_option($this->options_name );
		}
	}




	/*
	 *
	 * Return the title that will be used in the amenities listing
	 *
	 * @since 1.0.0
	 *
	 * @param none
	 * @return string amenities_title
	 */

	public function get_amenities_title() {
		$option = get_option( $this->options_name);
		return $option['hsp_amenities_title'];
	}

	/*
	 * Return the room listing room desciption length. It is used to
	 * determine how many words to display before truncating the
	 * description and displaying a "Read More" link.
	 *
	 * @since 1.0.0
	 *
	 * @param none
	 * @return integer room_desc_excerpt_len
	 */
	public function get_room_excerpt_len() {
		$option = get_option( $this->options_name);
		return $option['hsp_room_excerpt_len'];
	}

	/*
	 * Return "remove data on uninstall" flag. If true, all
	 * data and settings associated with the plugin are to be delete.
	 *
	 * @since 1.0.0
	 *
	 * @param none
	 * @return boolean remove_plugin_data_on_uninstall
	 */
	public function get_remove_data_on_uninstall() {
		$option = get_option( $this->options_name);
		return $option['hsp_remove_data_on_uninstall'];
	}



	/*
	 *
	 * Return the currency symbol
	 *
	 * @since 1.0.2
	 *
	 * @param none
	 * @return string currency symbol
	 */

	public function get_currency_symbol() {
		$option = get_option( $this->options_name);
		return $option['hsp_currency_symbol'];
	}

	/*
	 * Return street address 1
	 *
	 * @since 1.0.4
	 * @param none
	 * @return string street address 1
	 */
	public function get_street_address_1() {
		$option = get_option( $this->options_name);
		return $option['hsp_street_address_1'];
	}


	/*
	 * Return street address 2
	 *
	 * @since 1.0.4
	 * @param none
	 * @return string street address 2
	 */
	public function get_street_address_2() {
		$option = get_option( $this->options_name);
		return $option['hsp_street_address_2'];
	}

	/*
	 * Return city
	 *
	 * @since 1.0.4
	 * @param none
	 * @return string city
	 */
	public function get_city() {
		$option = get_option( $this->options_name);
		return $option['hsp_city'];
	}

	/*
	 * Return state
	 *
	 * @since 1.0.4
	 * @param none
	 * @return string city
	 */
	public function get_state() {
		$option = get_option( $this->options_name);
		return $option['hsp_state'];
	}


	/*
     * Return country
    *
    * @since 1.0.4
    * @param none
    * @return string country
    */
	public function get_country() {
		$option = get_option( $this->options_name);
		return $option['hsp_country'];
	}

	/*
	 * Return postal code
	 *
	 * @since 1.0.4
	 * @param none
	 * @return string postal code
	 */
	public function get_postal_code() {
		$option = get_option( $this->options_name);
		return $option['hsp_postal_code'];
	}

	/*
	 * Return google maps api key
	 *
	 * @since 1.0.4
	 * @param none
	 * @return string google maps api key
	 */
	public function get_google_maps_api_key() {
		$option = get_option( $this->options_name);
		return $option['hsp_google_maps_api_key'];
	}



	/*
	 * Return google maps default zoom
	 *
	 * @since 1.0.4
	 * @param none
	 * @return string google maps zoom
	 */
	public function get_google_maps_default_zoom() {
		$option = get_option( $this->options_name);
		return $option['hsp_google_maps_default_zoom'];
	}


	/*
	 * Return google maps latitude
	 *
	 * @since 1.0.4
	 * @param none
	 * @return string google maps lat
	 */
	public function get_google_maps_lat() {
		$option = get_option( $this->options_name);
		return $option['hsp_google_maps_lat'];
	}

	/*
	 * Return google maps longitude
	 *
	 * @since 1.0.4
	 * @param none
	 * @return string google maps lng
	 */
	public function get_google_maps_lng() {
		$option = get_option( $this->options_name);
		return $option['hsp_google_maps_lng'];
	}


	public function get_checkin_time() {
		$option = get_option( $this->options_name);
		return $option['hsp_checkin_time'];
	}

	public function get_checkout_time() {
		$option = get_option( $this->options_name);
		return $option['hsp_checkout_time'];
	}

	public function get_reservation_entry_point_slug() {
		$option = get_option( $this->options_name);
		return $option['hsp_reservation_entry_point_slug'];
	}

    public function get_enable_user_controls() {
		$option = get_option( $this->options_name);
		return $option['hsp_enable_user_controls'];
	}

	/*
	 * Return searchable amenities
	 *
	 * @since 1.0.6
	 * @param none
	 * @return string searchable amenities
	 */
	public function get_searchable_amenties() {
		$option = get_option( $this->options_name);
		return $option['hsp_searchable_amenities'];
	}

	/*
	 * Call back for action hook update_option_ . GUESTABA_HSP_OPTIONS_NAME
	 *
	 * Upon update of the plugin options, refresh the plugin geo options. The
	 * geo options are stored as a different options (GUESTABA_HSP_GEO_OPTIONS instead)
	 * to avoid an infinite loop.
	 **
	 * @since 1.0.4
	 */

	public function refresh_geocoding() {

		$options = get_option( GUESTABA_HSP_OPTIONS_NAME );
		$address_args = $options['hsp_street_address_1'] . ',+' . $options['hsp_city'] . ',+' . $options['hsp_state'] . ',+' . $options['hsp_postal_code'] ;
		$api_key = $options['hsp_google_geocode_api_key'];

		$url = str_replace (' ', '+', 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $address_args . '&key=' . $api_key ) ;


		$request = new WP_Http();

		$result = $request->get( $url );
		if ( ( isset( $result->errors ) && ( count( $result->errors ) > 0  ) ) || ( isset( $result['response']['code'] ) && $result['response']['code'] != 200 ) ) {
			error_log( __FILE__ . ', line ' . __LINE__ . ':' . 'Error retrieving URL, ' . $url );
			return;
		}
		$geocode_json = $result['body'];
		$geo_option = get_option( GUESTABA_HSP_GEO_OPTIONS_NAME );
		if ( $geo_option == false ) {
			$geo_option = array('geocode' => $geocode_json );
			add_option( GUESTABA_HSP_GEO_OPTIONS_NAME, $geo_option );
			return;
		}
		$geo_option['geocode'] = $geocode_json ;
		update_option( GUESTABA_HSP_GEO_OPTIONS_NAME, $geo_option );

	}

	/*
	 * This method defines the plugin setting page.
	 *
	 * @since 1.0.0
	 *
	 * @param none
	 * @return void
	 */
	public function settings_init(  ) {

		register_setting( 'hsp-settings-group', $this->options_name, array( $this, 'sanitize') );

		add_settings_section(
			'hsp-settings-general-section',
			__( 'Hospitality General Settings', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_settings_general_info'),
			'hsp-settings-page'
		);

		add_settings_field(
			'hsp_remove_data_at_uninstall',
			__( 'Remove plugin posts, settings, and other data on deactivation.', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_remove_data_render'),
			'hsp-settings-page',
			'hsp-settings-general-section'
		);

		// settings checkin/checkout
		add_settings_field(
			'hsp_checkin_time',
			__( 'Checkin Time', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_checkin_time_render'),
			'hsp-settings-page',
			'hsp-settings-general-section'
		);

		add_settings_field(
			'hsp_checkout_time',
			__( 'Checkout Time', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_checkout_time_render'),
			'hsp-settings-page',
			'hsp-settings-general-section'
		);

		/*
		 * Room listing options
		 */
		add_settings_section(
			'hsp-settings-list-section',
			__( 'Hospitality Room List Settings', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_settings_list_section_info'),
			'hsp-settings-page'
		);



		add_settings_field(
			'hsp_room_excerpt_len',
			__( 'Room excerpt maximum character count', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_room_excerpt_len_render'),
			'hsp-settings-page',
			'hsp-settings-list-section'
		);



		/*
		 * Room pages options
		 */
		add_settings_section(
			'hsp-settings-room-section',
			__( 'Hospitality Room Pages Settings', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_settings_room_section_info'),
			'hsp-settings-page'
		);



		add_settings_field(
			'hsp_amenities_title',
			__( 'Amenities list title', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_amenities_title_render'),
			'hsp-settings-page',
			'hsp-settings-room-section'
		);


		add_settings_field(
			'hsp_currency_symbol',
			__( 'Currency symbol', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_currency_symbol_render'),
			'hsp-settings-page',
			'hsp-settings-room-section'
		);

		/*
		 * Location/address options
		 */
		add_settings_section(
			'hsp-settings-address-section',
			__( 'Hospitality Address Settings', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_settings_address_section_info'),
			'hsp-settings-page'
		);

		// settings field for street address 1
		add_settings_field(
			'hsp_street_address_1',
			__( 'Street Address 1', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_street_address_1_render'),
			'hsp-settings-page',
			'hsp-settings-address-section'
		);

		// settings field for street address 2
		add_settings_field(
			'hsp_street_address_2',
			__( 'Street Address 2', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_street_address_2_render'),
			'hsp-settings-page',
			'hsp-settings-address-section'
		);

		// add settings field for city
		add_settings_field(
			'hsp_city',
			__( 'City', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_city_render'),
			'hsp-settings-page',
			'hsp-settings-address-section'
		);

		// add settings field for state
		add_settings_field(
			'hsp_state',
			__( 'State', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_state_render'),
			'hsp-settings-page',
			'hsp-settings-address-section'
		);

		// settings field for country
		add_settings_field(
			'hsp_country',
			__( 'Country', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_country_render'),
			'hsp-settings-page',
			'hsp-settings-address-section'
		);

		// settings field for postal code
		add_settings_field(
			'hsp_postal_code',
			__( 'Postal Code (Zip)', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_postal_code_render'),
			'hsp-settings-page',
			'hsp-settings-address-section'
		);


		// settings field google maps api key
		if ( GUESTABA_HSP_GMAP_API_SETTING_ENABLED ) {
			add_settings_field(
				'hsp_google_maps_api_key',
				__( 'Google Maps API Key', GUESTABA_HSP_TEXTDOMAIN ),
				array($this, 'hsp_google_maps_api_key_render'),
				'hsp-settings-page',
				'hsp-settings-address-section'
			);
		}




		// settings field google maps center latitude
		add_settings_field(
			'hsp_google_maps_lat',
			__( 'Location latitude', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_google_maps_lat_render'),
			'hsp-settings-page',
			'hsp-settings-address-section'
		);

		// settings field google maps center longitude
		add_settings_field(
			'hsp_google_maps_lng',
			__( 'Location longitude', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_google_maps_lng_render'),
			'hsp-settings-page',
			'hsp-settings-address-section'
		);

		// settings field google maps zoom
		add_settings_field(
			'hsp_google_maps_default_zoom',
			__( 'Google Maps Default Zoom', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_google_maps_default_zoom_render'),
			'hsp-settings-page',
			'hsp-settings-address-section'
		);


		/*
		 * Room search options
		 */
		add_settings_section(
			'hsp-settings-search-section',
			__( 'Hospitality Room Search Settings', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_settings_search_section_info'),
			'hsp-settings-page'
		);

		add_settings_field(
			'hsp_searchable_amenities',
			__( 'Searchable Amenities', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_searchable_amenities_render'),
			'hsp-settings-page',
			'hsp-settings-search-section'
		);

		add_settings_field(
			'hsp_reservation_entry_point_slug',
			__( 'Reservation Entry Point Slug', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_reservation_entry_point_slug_render'),
			'hsp-settings-page',
			'hsp-settings-search-section'
		);



		/*
		 * Payment gateway options
		 */
		add_settings_section(
			'hsp-settings-payment-gateway-section',
			__( 'Payment Gateway Settings', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_settings_payment_gateway_section_info'),
			'hsp-settings-page'
		);

		add_settings_field(
			'hsp_payment_gateway_name',
			__( 'Gateway Name', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_payment_gateway_name_render'),
			'hsp-settings-page',
			'hsp-settings-payment-gateway-section'
		);

		add_settings_field(
			'hsp_payment_gateway_username',
			__( 'Gateway User Name', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_payment_gateway_username_render'),
			'hsp-settings-page',
			'hsp-settings-payment-gateway-section'
		);

		add_settings_field(
			'hsp_payment_gateway_password',
			__( 'Gateway Password', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_payment_gateway_password_render'),
			'hsp-settings-page',
			'hsp-settings-payment-gateway-section'
		);

		add_settings_field(
			'hsp_payment_gateway_signature',
			__( 'Gateway Signature', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_payment_gateway_signature_render'),
			'hsp-settings-page',
			'hsp-settings-payment-gateway-section'
		);

		add_settings_field(
			'hsp_payment_gateway_test_mode',
			__( 'Gateway Test Mode', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_payment_gateway_test_mode_render'),
			'hsp-settings-page',
			'hsp-settings-payment-gateway-section'
		);

		add_settings_field(
			'hsp_payment_gateway_cancel_url',
			__( 'Gateway Cancel URL', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_payment_gateway_cancel_url_render'),
			'hsp-settings-page',
			'hsp-settings-payment-gateway-section'
		);

		add_settings_field(
			'hsp_payment_gateway_return_url',
			__( 'Gateway Return URL', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_payment_gateway_return_url_render'),
			'hsp-settings-page',
			'hsp-settings-payment-gateway-section'
		);

	    add_settings_field(
			'hsp_payment_gateway_currency',
			__( 'Gateway Currency', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_payment_gateway_currency_render'),
			'hsp-settings-page',
			'hsp-settings-payment-gateway-section'
		);

		add_settings_field(
			'hsp_payment_gateway_enable_offline',
			__( 'Enable offline payments', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_payment_gateway_enable_offline_render'),
			'hsp-settings-page',
			'hsp-settings-payment-gateway-section'
		);

		add_settings_field(
			'hsp_payment_gateway_offline_instructions',
			__( 'Offline payment instructions', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_payment_gateway_offline_instructions_render'),
			'hsp-settings-page',
			'hsp-settings-payment-gateway-section'
		);


		add_settings_field(
			'hsp_confirmation_email_message',
			__( 'Confirmation email message', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_confirmation_email_message_render'),
			'hsp-settings-page',
			'hsp-settings-payment-gateway-section'
		);

		// User registration settings
		add_settings_section(
			'hsp-settings-registration-section',
			__( 'Hospitality User Registration Settings', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_settings_registration_section_info'),
			'hsp-settings-page'
		);

		add_settings_field(
			'hsp_registration_message',
			__( 'Registration message', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_registration_message_render'),
			'hsp-settings-page',
			'hsp-settings-registration-section'
		);

			add_settings_field(
			'hsp_enable_user_controls',
			__( 'Enable user registration and login controls', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_enable_user_controls_render'),
			'hsp-settings-page',
			'hsp-settings-registration-section'
		);

		// Tax and fees settings
		add_settings_section(
			'hsp-settings-taxes-fees-section',
			__( 'Hospitality Tax and Fee Settings', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_settings_tax_fee_section_info'),
			'hsp-settings-page'
		);

		add_settings_field(
			'hsp_tax_amount',
			__( 'Tax amount', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_tax_amount_render'),
			'hsp-settings-page',
			'hsp-settings-taxes-fees-section'
		);

		add_settings_field(
			'hsp_tax_is_percentage',
			__( 'Apply tax as a percentage of room and fee total', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_tax_is_percentage_render'),
			'hsp-settings-page',
			'hsp-settings-taxes-fees-section'
		);

        add_settings_field(
			'hsp_fee_amount',
			__( 'Fee amount', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_fee_amount_render'),
			'hsp-settings-page',
			'hsp-settings-taxes-fees-section'
		);

		add_settings_field(
			'hsp_fee_is_percentage',
			__( 'Apply fee as a percentage room total', GUESTABA_HSP_TEXTDOMAIN ),
			array($this, 'hsp_fee_is_percentage_render'),
			'hsp-settings-page',
			'hsp-settings-taxes-fees-section'
		);





	}

	/*
	 * Calls add_options_page to register the page and menu item.
	 *
	 * @since 1.0.0
	 *
	 * @param none
	 * @return integer room_desc_excerpt_len
	 */
	public function add_hsp_options_page( ) {

		// Add the top-level admin menu
		$page_title = 'Hospitality Plugin Setings';
		$menu_title = 'Hospitality';
		$capability = 'manage_options';
		$menu_slug = 'hospitality-settings';
		$function = 'settings_page';
		add_options_page($page_title, $menu_title, $capability, $menu_slug, array($this, $function)) ;


	}

	/*
	 * Defines and displays the plugin settings page.
	 * @since 1.0.0
	 *
	 * @param none
	 * @return none
	 */
	public function settings_page(  ) {

		$this->add_option_defaults();

		?>
		<div class="wrap">
            <form action='options.php' method='post'>
                <div id="icon-options-general" class="icon32"></div>
                    <h1><?php esc_attr_e( 'Hospitality Settings', 'wp_admin_style' ); ?></h1>
                    <div id="hsp-settings-container">
                        <?php

                        settings_fields( 'hsp-settings-group' );
                        do_settings_sections( 'hsp-settings-page' );
                        submit_button();
                        ?>
                    </div>
                    <div id="hsp-settings-info-container">
                        <h2 class="hndle"><span><?php esc_attr_e(
                                            'Hospitality from Guestaba', 'wp_admin_style'
                                        ); ?></span></h2>
                                        <div class="inside">
                        <p><em> <?php esc_attr_e( 'Version:', 'wp_admin_style');?> <?php echo $this->version ?></em></p>
                        <h3><?php esc_attr_e('Help Improve this Plugin', 'wp_admin_style');?></h3>
                            <p><?php esc_attr_e('Send us your ideas, feature requests and...donations :)', 'wp_admin_style'); ?></p>
                            <h3><?php esc_attr_e('Contact Us', 'wp_admin_style');?></h3>
                            <a id="hsp-setting-contact" href="https://www.guestaba.com/" target="_blank"><p><span class="dashicons dashicons-admin-site"></span><?php esc_attr_e('Website', 'wp_admin_style'); ?></p></a>
                            <a id="hsp-setting-contact" href="https://twitter.com/guestaba" target="_blank"><p><span class="dashicons dashicons-twitter"></span><?php esc_attr_e('Twitter', 'wp_admin_style'); ?></p></a>
                            <a id="hsp-setting-contact" href="https://www.facebook.com/GuestabaforWordpress/" target="_blank"><p><span class="dashicons dashicons-facebook"></span><?php esc_attr_e('Facebook', 'wp_admin_style'); ?></p></a>


                    <div id="hsp-donate">
                            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                <input type="hidden" name="cmd" value="_s-xclick">
                                <input type="hidden" name="hosted_button_id" value="FUWXV5MTNZWW4">
                                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                            </form>
                        </div>
                    </div>
                </div>

            </form>
            <?php
                if ( defined( 'SCRIPT_DEBUG' ) ) {
                ?>
                    <button id="gen-demo">Generate Demo Data</button>
                <?php
                }
            ?>
		</div>
		<?php

	}



	/*
	 * Render the remove data on unsinstal checkbox field.
	 * @since 1.0.0
	 */
	public function hsp_remove_data_render(  ) {

		$content = $this->get_option('hsp_remove_data_on_uninstall', $this->default_remove_data_on_uninstall );
		?>
		<label for="remove_hsp_data_input">
		<input id="remove_hsp_data_input" type="checkbox" name="guestaba_hsp_settings[hsp_remove_data_on_uninstall]" <?php checked( $content, 1 ); ?> value='1'>
		<span>Leave this unchecked unless you really want to remove the posts you have created using this plugin.</span></label>
		<?php

	}

	/*
	 * Render the amenities title field.
	 * @since 1.0.0
	 */
	public function hsp_amenities_title_render(  ) {

		$content = $this->get_option('hsp_amenities_title', 'Amenities' );
		?>
		<input type="text" size="40" name="guestaba_hsp_settings[hsp_amenities_title]" value="<?php echo $content; ?>">
		<?php

	}

	/*
	 * Render the room description excerpt length field
	 * @since 1.0.0
	 */

	public function hsp_room_excerpt_len_render(  ) {

		$content = $this->get_option('hsp_room_excerpt_len', $this->default_excerpt_len );
		?>
		<input type="text" size="4" name="guestaba_hsp_settings[hsp_room_excerpt_len]" value='<?php echo $content; ?>'>
		<?php

	}

	/*
	* Render the currency symbol field
	* @since 1.0.2
	 */
	public function hsp_currency_symbol_render(  ) {

		$content = $this->get_option('hsp_currency_symbol','$' );
		?>
		<input type="text" size="1" class="small-text" name="guestaba_hsp_settings[hsp_currency_symbol]" value='<?php echo $content; ?>'>
		<?php

	}

	/*
	* Render the street address 1 field
	* @since 1.0.4
	 */
	public function hsp_street_address_1_render(  ) {

		$content = $this->get_option('hsp_street_address_1','701 SW 6th Ave' );
		?>
		<input id="hsp-settings-street-1" class="regular-text" type="text" size="40" name="guestaba_hsp_settings[hsp_street_address_1]" value="<?php echo $content ; ?>">
		<?php

	}


	/*
	 *
	 * Render the street address 2 field
	 * @since 1.0.4
	 *
	 */
	public function hsp_street_address_2_render(  ) {

		$content = $this->get_option('hsp_street_address_2','' );
		?>
		<input id="hsp-settings-street-2" class="regular-text" type="text" size="40" name="guestaba_hsp_settings[hsp_street_address_2]" value="<?php echo $content; ?>">
		<?php

	}

	/*
	 *
	 * Render the city field
	 * @since 1.0.4
	 *
	 */
	public function hsp_city_render(  ) {

		$content = $this->get_option('hsp_city','Portland' );
		?>
		<input id="hsp-settings-city" class="regular-text" type="text" size="40" name="guestaba_hsp_settings[hsp_city]" value="<?php echo $content; ?>">
		<?php

	}


	/*
	 *
	 * Render the state field
	 * @since 1.0.4
	 *
	 */
	public function hsp_state_render(  ) {

        $content = $this->get_option('hsp_state','Oregon' );

		?>
		<input id="hsp-settings-state" class="regular-text" type="text" size="20" name="guestaba_hsp_settings[hsp_state]" value="<?php echo $content; ?>">
		<?php

	}

	/*
	 *
	 * Render the country field
	 * @since 1.0.4
	 *
	 */
	public function hsp_country_render(  ) {


		$content = $this->get_option('hsp_country', __('United States', GUESTABA_HSP_TEXTDOMAIN ));
		?>
		<input id="hsp-settings-country" class="regular-text" type="text" size="30" name="guestaba_hsp_settings[hsp_country]"
		       value="<?php echo $content; ?>">
		<?php
	}

	/*
	 *
	 * Render the postal code field
	 * @since 1.0.4
	 *
	 */
	public function hsp_postal_code_render(  ) {

		$content = $this->get_option('hsp_postal_code','97204' );
		?>
		<input id="hsp-settings-postal-code" class="regular-text" type="text" size="16" name="guestaba_hsp_settings[hsp_postal_code]"
		       value="<?php echo $content; ?>">
		<?php
	}


	/*
	 *
	 * Render the google maps api key field
	 * @since 1.0.4
	 *
    */
	public function hsp_google_maps_api_key_render(  ) {

		$content = $this->get_option('hsp_google_maps_api_key','' );
		?>
		<input id="hsp-settings-google-maps-api-key" type="text" class="regular-text" size="50" name="guestaba_hsp_settings[hsp_google_maps_api_key]"
		       value="<?php echo $content ; ?>">
		<p><a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"><?php _e('How to get an API key.', GUESTABA_HSP_TEXTDOMAIN )?></a></p>
		<?php
	}



	/*
	* Render the google maps default zoom field
	* @since 1.0.4
	 */
	public function hsp_google_maps_default_zoom_render(  ) {


		$content = $this->get_option('hsp_google_maps_default_zoom', 8 );
		?>
		<input id="hsp-settings-zoom" type="text" size="2" class="regular-text" name="guestaba_hsp_settings[hsp_google_maps_default_zoom]" value='<?php echo $content ; ?>'>
	<?php

	}

	/*
	* Render the google maps latitude field
	* @since 1.0.4
	 */
	public function hsp_google_maps_lat_render(  ) {

		$content = $this->get_option('hsp_google_maps_lat', GUESTABA_HSP_DEFAULT_LAT );
		?>
		<input id="hsp-settings-lat" type="number" name="guestaba_hsp_settings[hsp_google_maps_lat]" value="<?php echo $content; ?>" min="0" max="90" step="0.0000001">
		<button id="hsp-settings-geocode-button" class="hsp-geocode-button" title="<?php _e('Click to geocode the location address entered above.', GUESTABA_HSP_TEXTDOMAIN ); ?>"><?php _e('Geocode Address', GUESTABA_HSP_TEXTDOMAIN ) ;?></button>
		<div id="hsp-settings-geocode-message" class="hsp-settings-message"></div>
		<?php

	}

	/*
	* Render the google maps longitude field
	* @since 1.0.4
	 */
	public function hsp_google_maps_lng_render(  ) {

		$content = $this->get_option('hsp_google_maps_lng', GUESTABA_HSP_DEFAULT_LNG );
		?>
		<input id="hsp-settings-lng" type="number" name="guestaba_hsp_settings[hsp_google_maps_lng]" value="<?php echo $content; ?>" min="-180" max="180" step="0.0000001">
		<?php

	}

	public function hsp_checkin_time_render(  ) {

        $time_value = $this->get_option('hsp_checkin_time', __('4:00pm', GUESTABA_HSP_TEXTDOMAIN) );

		?>
		<input id="hsp-settings-checkin-time" class="gst_time_input" type="text" size="16" name="guestaba_hsp_settings[hsp_checkin_time]"
		       value="<?php echo $time_value; ?>">
		<?php
	}

	public function hsp_checkout_time_render(  ) {

        $time_value = $this->get_option('hsp_checkout_time', __('11:00am', GUESTABA_HSP_TEXTDOMAIN) );
		?>
		<input id="hsp-settings-checkout-time" class="gst_time_input" type="text" size="16" name="guestaba_hsp_settings[hsp_checkout_time]"
		       value="<?php echo $time_value; ?>">
		<?php
	}



	public function hsp_searchable_amenities_render(  ) {

		$content = $this->get_option('hsp_searchable_amenities', '' );
		?>
		<textarea id="hsp-settings-searchable-amenities"  cols="40" rows="10" name="guestaba_hsp_settings[hsp_searchable_amenities]"> <?php echo $content; ?></textarea>
		<?php

	}

	public function hsp_payment_gateway_name_render() {

		$gateway_name = $this->get_option('hsp_payment_gateway_name', '' );
		?>
		<select id="hsp-settings-gateway-name" name="guestaba_hsp_settings[hsp_payment_gateway_name]">
			<?php
				$selected = '';
				foreach ( Payment_Gateway_Factory::get_gateway_names() as $gateway => $gateway_display ) {
					if ( $gateway_name == $gateway ) {
						$selected = 'selected';
					}
					else {
					    $selected = '';
					}
					?>
					<option value="<?php echo $gateway . '" ' . $selected ?>><?php echo $gateway_display ; ?></option>
					<?php
				}
			?>
		</select>
		<?php

	}

	public function hsp_payment_gateway_test_mode_render() {

        $test_mode = $this->get_option('hsp_payment_gateway_test_mode', 'true' );

		?>
		<select id="hsp-settings-gateway-test-mode" name="guestaba_hsp_settings[hsp_payment_gateway_test_mode]">
			<?php
				$selected = '';
				foreach ( Payment_Gateway_Factory::get_test_mode_options() as $test_mode_option => $test_mode_option_display ) {
					if ( $test_mode == $test_mode_option ) {
						$selected = 'selected';
					}
					else {
					    $selected = '';
					}
					?>
					<option value="<?php echo $test_mode_option . '" ' . $selected ?>><?php echo $test_mode_option_display ; ?></option>
					<?php
				}
			?>
		</select>
		<?php

	}

	public function hsp_payment_gateway_enable_offline_render() {


		$enable_offline = $this->get_option('hsp_payment_gateway_enable_offline', GUESTABA_HSP_OFF );
		?>
		<select id="hsp-settings-gateway-enable-offline" name="guestaba_hsp_settings[hsp_payment_gateway_enable_offline]">
			<?php
				$selected = '';
				foreach ( Payment_Gateway_Factory::get_enable_offline_options() as $enable_offline_option => $enable_offline_option_display ) {
					if ( $enable_offline == $enable_offline_option ) {
						$selected = 'selected';
					}
					else {
					    $selected = '';
					}
					?>
					<option value="<?php echo $enable_offline_option . '" ' . $selected ?>><?php echo $enable_offline_option_display ; ?></option>
					<?php
				}
			?>
		</select>
		<?php

	}

	public function hsp_payment_gateway_username_render(  ) {

		$content = $this->get_option('hsp_payment_gateway_username', '' );
		?>
		<input id="hsp-payment-gateway-username" type="text" size="40" name="guestaba_hsp_settings[hsp_payment_gateway_username]" value="<?php echo $content; ?>">
		<?php

	}


	public function hsp_payment_gateway_password_render(  ) {

        $content = $this->get_option('hsp_payment_gateway_password', '' );
		?>
		<input id="hsp-payment-gateway-password" type="text" size="40" name="guestaba_hsp_settings[hsp_payment_gateway_password]" value="<?php echo $content; ?>">
		<?php

	}


	public function hsp_payment_gateway_signature_render(  ) {

        $content = $this->get_option('hsp_payment_gateway_signature', '' );
		?>
		<input id="hsp-payment-gateway-signature" type="text" size="40" name="guestaba_hsp_settings[hsp_payment_gateway_signature]" value="<?php echo $content; ?>">
		<?php

	}


	public function hsp_payment_gateway_return_url_render(  ) {

        $content = $this->get_option('hsp_payment_gateway_return_url', '' );

		?>
		<input id="hsp-payment-gateway-return-url" type="text" size="40" name="guestaba_hsp_settings[hsp_payment_gateway_return_url]" value="<?php echo $content; ?>">
		<?php

	}


	public function hsp_payment_gateway_cancel_url_render(  ) {

		$content = $this->get_option('hsp_payment_gateway_cancel_url', '' );
		?>
		<input id="hsp-payment-gateway-cancel-url" type="text" size="40" name="guestaba_hsp_settings[hsp_payment_gateway_cancel_url]" value="<?php echo $content ; ?>">
		<?php

	}

	public function hsp_payment_gateway_currency_render(  ) {

	    $content = $this->get_option('hsp_payment_gateway_currency', GUESTABA_DEFAULT_CURRENCY );
		?>
		<input id="hsp-payment-gateway-currency" type="text" size="3" name="guestaba_hsp_settings[hsp_payment_gateway_currency]" value="<?php echo $content; ?>">
		<?php

	}

	public function hsp_payment_gateway_offline_instructions_render(  ) {

	    $content = $this->get_option('hsp_payment_gateway_offline_instructions', '' );

		?>
		<textarea id="hsp-settings-payment-gateway-offline-instructions"  cols="40" rows="10" name="guestaba_hsp_settings[hsp_payment_gateway_offline_instructions]"> <?php echo $content; ?></textarea>
		<?php

	}

	public function hsp_confirmation_email_message_render(  ) {

        $content = $this->get_option('hsp_confirmation_email_message', '' );

		?>
		<textarea id="hsp-settings-confirmation-email-message"  class="all-options" cols="40" rows="10" name="guestaba_hsp_settings[hsp_confirmation_email_message]"> <?php echo $content ; ?></textarea>
		<?php

	}

	public function hsp_registration_message_render(  ) {

		$options = get_option( $this->options_name );
		$content = "";
		if ( isset($options['hsp_registration_message']) ) {
				$content = $options['hsp_registration_message'];
		}

		?>
		<textarea id="hsp-settings-registration-message"  cols="40" rows="10" name="guestaba_hsp_settings[hsp_registration_message]"> <?php echo $content; ?></textarea>
		<?php

	}


	  public function hsp_enable_user_controls_render(  ) {

		$options = get_option( $this->options_name );

        $content = false;
	    if ( isset( $options['hsp_enable_user_controls'] ) ) {
	        $content = $options['hsp_enable_user_controls'];
	    }

		?>
		<label for="hsp-enable-user-controls">
		<input id="hsp-enable-user-controls" type="checkbox" name="guestaba_hsp_settings[hsp_enable_user_controls]" <?php checked( $content, "true", true ); ?> value='true'>
		<span><?php _e('Check this to enable user registration and login links above search form.', GUESTABA_HSP_TEXTDOMAIN) ;?></span></label>
		<?php

	}

	public function hsp_tax_amount_render( ) {

		$options = get_option( $this->options_name );

	    $content = 0;
	    if ( isset( $options['hsp_tax_amount'] ) ) {
	        $content = $options['hsp_tax_amount'];
	    }
		?>
		<input id="hsp-settings-tax-amount" type="number" name="guestaba_hsp_settings[hsp_tax_amount]" value="<?php echo $content; ?>" min="0" step="0.01">
		<?php

	}

	public function hsp_fee_amount_render( ) {

		$options = get_option( $this->options_name );

	    $content = 0;
	    if ( isset( $options['hsp_fee_amount'] ) ) {
	        $content = $options['hsp_fee_amount'];
	    }
		?>
		<input id="hsp-settings-fee-amount" class="small-text" type="number" name="guestaba_hsp_settings[hsp_fee_amount]" value="<?php echo $content; ?>" min="0" step="0.01">
		<?php

	}

    public function hsp_tax_is_percentage_render(  ) {

		$options = get_option( $this->options_name );

        $content = 0;
	    if ( isset( $options['hsp_tax_is_percentage'] ) ) {
	        $content = $options['hsp_tax_is_percentage'];
	    }

		?>
		<label for="hsp-settings-tax-is-percentage">
		<input id="hsp-settings-tax-is-percentage"  type="checkbox" name="guestaba_hsp_settings[hsp_tax_is_percentage]" <?php checked( $content, 1 ); ?> value='1'>
		<span><?php _e('Check this to apply tax as a percentage instead an absolute amount.', GUESTABA_HSP_TEXTDOMAIN) ;?></span></label>
		<?php

	}

    public function hsp_fee_is_percentage_render(  ) {

		$options = get_option( $this->options_name );

        $content = 0;
	    if ( isset( $options['hsp_fee_is_percentage'] ) ) {
	        $content = $options['hsp_fee_is_percentage'];
	    }

		?>
		<label for="hsp-settings-fee-is-percentage">
		<input id="hsp-settings-fee-is-percentage" type="checkbox" name="guestaba_hsp_settings[hsp_fee_is_percentage]" <?php checked( $content, 1 ); ?> value='1'>
		<span><?php _e('Check this to apply fee as a percentage instead an absolute amount.', GUESTABA_HSP_TEXTDOMAIN) ;?></span></label>
		<?php

	}

	public function hsp_reservation_entry_point_slug_render(  ) {

		$options = get_option( $this->options_name );

		$content = GUESTABA_DEFAULT_RESERVATION_SLUG ;
		if ( isset( $options['hsp_reservation_entry_point_slug'] ) ) {
	        $content = $options['hsp_reservation_entry_point_slug'];
	    }

		?>
		<input id="hsp-reservation-entry-point-slug" class="regular-text" type="text" size="40" name="guestaba_hsp_settings[hsp_reservation_entry_point_slug]" value="<?php echo $content; ?>">
		<?php

	}







	/*
	 * Sanitize user input before passing values on to update options.
	 * @since 1.0.0
	 */
	public function sanitize( $input ) {

		$new_input = array();

		if( isset( $input['hsp_remove_data_on_uninstall'] ) ) {
        	 $new_input['hsp_remove_data_on_uninstall'] = sanitize_text_field( $input['hsp_remove_data_on_uninstall'] );
        }
        else {
        	// set to default
        	$new_input['hsp_remove_data_on_uninstall'] = false ;
        }


        if( isset( $input['hsp_amenities_title'] ) )
            $new_input['hsp_amenities_title'] = sanitize_text_field( $input['hsp_amenities_title'] );

        if( isset( $input['hsp_room_excerpt_len'] ) )
            $new_input['hsp_room_excerpt_len'] = absint( $input['hsp_room_excerpt_len'] );

		if( isset( $input['hsp_currency_symbol'] ) )
			$new_input['hsp_currency_symbol'] = sanitize_text_field( $input['hsp_currency_symbol'] );

		if( isset( $input['hsp_street_address_1'] ) )
			$new_input['hsp_street_address_1'] = sanitize_text_field( $input['hsp_street_address_1'] );

		if( isset( $input['hsp_street_address_2'] ) )
			$new_input['hsp_street_address_2'] = sanitize_text_field( $input['hsp_street_address_2'] );

		if( isset( $input['hsp_city'] ) )
			$new_input['hsp_city'] = sanitize_text_field( $input['hsp_city'] );

		if( isset( $input['hsp_state'] ) )
			$new_input['hsp_state'] = sanitize_text_field( $input['hsp_state'] );

		if( isset( $input['hsp_country'] ) )
			$new_input['hsp_country'] = sanitize_text_field( $input['hsp_country'] );

		if( isset( $input['hsp_postal_code'] ) )
			$new_input['hsp_postal_code'] = sanitize_text_field( $input['hsp_postal_code'] );

		if( isset( $input['hsp_google_maps_api_key'] ) )
			$new_input['hsp_google_maps_api_key'] = sanitize_text_field( $input['hsp_google_maps_api_key'] );

		if( isset( $input['hsp_google_geocode_api_key'] ) )
			$new_input['hsp_google_geocode_api_key'] = sanitize_text_field( $input['hsp_google_geocode_api_key'] );

		if( isset( $input['hsp_google_maps_default_zoom'] ) )
			$new_input['hsp_google_maps_default_zoom'] = intval( $input['hsp_google_maps_default_zoom'] );

		if( isset( $input['hsp_google_maps_lat'] ) )
			$new_input['hsp_google_maps_lat'] = floatval( $input['hsp_google_maps_lat'] );

		if( isset( $input['hsp_google_maps_lng'] ) )
			$new_input['hsp_google_maps_lng'] = floatval( $input['hsp_google_maps_lng'] );

		if( isset( $input['hsp_checkin_time'] ) )
			$new_input['hsp_checkin_time'] = sanitize_text_field( $input['hsp_checkin_time'] );

		if( isset( $input['hsp_checkout_time'] ) )
			$new_input['hsp_checkout_time'] = sanitize_text_field( $input['hsp_checkout_time'] );

		if( isset( $input['hsp_searchable_amenities'] ) )
			$new_input['hsp_searchable_amenities'] = sanitize_text_field( $input['hsp_searchable_amenities'] );

		if( isset( $input['hsp_payment_gateway_name'] ) )
			$new_input['hsp_payment_gateway_name'] = sanitize_text_field( $input['hsp_payment_gateway_name'] );

		if( isset( $input['hsp_payment_gateway_username'] ) )
			$new_input['hsp_payment_gateway_username'] = sanitize_text_field( $input['hsp_payment_gateway_username'] );

		if( isset( $input['hsp_payment_gateway_password'] ) )
			$new_input['hsp_payment_gateway_password'] = sanitize_text_field( $input['hsp_payment_gateway_password'] );

		if( isset( $input['hsp_payment_gateway_signature'] ) )
			$new_input['hsp_payment_gateway_signature'] = sanitize_text_field( $input['hsp_payment_gateway_signature'] );

		if( isset( $input['hsp_payment_gateway_return_url'] ) )
			$new_input['hsp_payment_gateway_return_url'] = sanitize_text_field( $input['hsp_payment_gateway_return_url'] );

		if( isset( $input['hsp_payment_gateway_cancel_url'] ) )
			$new_input['hsp_payment_gateway_cancel_url'] = sanitize_text_field( $input['hsp_payment_gateway_cancel_url'] );

		if( isset( $input['hsp_payment_gateway_test_mode'] ) )
			$new_input['hsp_payment_gateway_test_mode'] = sanitize_text_field( $input['hsp_payment_gateway_test_mode'] );

        if( isset( $input['hsp_payment_gateway_currency'] ) )
			$new_input['hsp_payment_gateway_currency'] = sanitize_text_field( $input['hsp_payment_gateway_currency'] );

        if( isset( $input['hsp_payment_gateway_enable_offline'] ) )
			$new_input['hsp_payment_gateway_enable_offline'] = sanitize_text_field( $input['hsp_payment_gateway_enable_offline'] );

        if( isset( $input['hsp_payment_gateway_offline_instructions'] ) )
			$new_input['hsp_payment_gateway_offline_instructions'] = sanitize_text_field( $input['hsp_payment_gateway_offline_instructions'] );

        if( isset( $input['hsp_confirmation_email_message'] ) )
			$new_input['hsp_confirmation_email_message'] = sanitize_text_field( $input['hsp_confirmation_email_message'] );

        if( isset( $input['hsp_registration_message'] ) )
			$new_input['hsp_registration_message'] = sanitize_text_field( $input['hsp_registration_message'] );

        if( isset( $input['hsp_enable_user_controls'] ) )
			$new_input['hsp_enable_user_controls'] = sanitize_text_field( $input['hsp_enable_user_controls'] );


        if( isset( $input['hsp_tax_amount'] ) )
			$new_input['hsp_tax_amount'] = floatval( $input['hsp_tax_amount'] );

        if( isset( $input['hsp_tax_is_percentage'] ) )
			$new_input['hsp_tax_is_percentage'] = sanitize_text_field( $input['hsp_tax_is_percentage'] );

        if( isset( $input['hsp_fee_amount'] ) )
			$new_input['hsp_fee_amount'] = floatval( $input['hsp_fee_amount'] );

        if( isset( $input['hsp_fee_is_percentage'] ) )
			$new_input['hsp_fee_is_percentage'] = sanitize_text_field( $input['hsp_fee_is_percentage'] );

        if( isset( $input['hsp_reservation_entry_point_slug'] ) )
			$new_input['hsp_reservation_entry_point_slug'] = sanitize_text_field( $input['hsp_reservation_entry_point_slug'] );



		return $new_input ;
	}

	/*
	 * Render general settings section info.
	 * @since 1.0.0
	 */
	public function hsp_settings_general_info () {
		echo '<p>' . __("General settings for Hospitality Plugin", GUESTABA_HSP_TEXTDOMAIN) . '</p>';
	}

	/*
	 * Render room listing settings section info.
	 * @since 1.0.0
	 */
	public function hsp_settings_list_section_info () {
		echo '<p>' . __("Default settings for room listing options.", GUESTABA_HSP_TEXTDOMAIN) . '</p>';
	}

	/*
	 * Render room page settings section info.
	 * @since 1.0.0
	 */
	public function hsp_settings_room_section_info () {
		echo '<p>' . __("Default settings for room pages.", GUESTABA_HSP_TEXTDOMAIN) . '</p>';
	}

	/*
     * Render location/address settings section info.
     * @since 1.0.4
     */
	public function hsp_settings_address_section_info () {
		echo '<p>' . __("Address settings for location map.", GUESTABA_HSP_TEXTDOMAIN) . '</p>';
	}

	public function hsp_settings_search_section_info () {
		echo '<p>' . __("Room search settings.", GUESTABA_HSP_TEXTDOMAIN) . '</p>';
	}

	public function hsp_settings_payment_gateway_section_info () {
		echo '<p>' . __("Payment gateway settings.", GUESTABA_HSP_TEXTDOMAIN) . '</p>';
	}


	public function hsp_settings_registration_section_info () {
		echo '<p>' . __("User registration settings.", GUESTABA_HSP_TEXTDOMAIN) . '</p>';
	}

	public function hsp_settings_tax_fee_section_info () {
		echo '<p>' . __("Tax and fee settings.", GUESTABA_HSP_TEXTDOMAIN) . '</p
		h2>';
	}



	/*
	 * Places link to settings page under the Plugins->Installed Plugins listing entry.
	 * It is intended to be called via add_filter.
	 *
	 * @param array $links an array of existing action links.
	 *
	 * @return $links with
	 * @since 1.0.0
	 */
	public function action_links( $links ) {

		array_unshift( $links,'<a href="https://www.guestaba.com/knowledgebase/" target="_blank">FAQ</a>' );
		array_unshift($links, '<a href="'. get_admin_url(null, 'options-general.php?page=hospitality-settings') .'">Settings</a>');

    	return $links;


	}

	private function get_option( $option_name, $default ) {

	    $options = get_option( $this->options_name );

        $content = $default ;
	    if ( isset( $options[ $option_name ] ) ) {
	        $content = $options[ $option_name ];
	    }

	    return $content;
	}


}

?>
