<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://guestaba.com
 * @since      1.0.0
 *
 * @package    Hospitality
 * @subpackage Hospitality/admin
 * @author     Wes Kempfer <wkempferjr@tnotw.com>
 */
class Hospitality_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $hospitality    The ID of this plugin.
	 */
	private $hospitality;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/** Handle for hospitality-admin javascript.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $hospitality_admin_js_handle
	 */
	private $hospitality_admin_js_handle = 'hospitality-room-admin-js';
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $hospitality       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $hospitality, $version ) {

		$this->hospitality = $hospitality;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		$css_suffix = ( defined( 'SCRIPT_DEBUG' ) ) ? '.min.css' : '.css';

		wp_enqueue_style('jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
		wp_enqueue_style( 'font-awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->hospitality, plugin_dir_url( __FILE__ ) . 'css/hospitality-admin' . $css_suffix, array(), $this->version, 'all' );
		wp_enqueue_style( 'gst-jquery-timepicker', plugin_dir_url( __FILE__ ) . 'css/jquery.timepicker.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$js_suffix = ( defined( 'SCRIPT_DEBUG' ) ) ? '.min.js' : '.js';

		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script( 'gst-character-counter', plugin_dir_url( __FILE__ ) . 'js/vendor/jquery.simplyCountable.min.js', array( 'jquery' ), $this->version, false );

		$this->queue_google_maps();

		wp_enqueue_script( 'gst-jquery-timepicker', plugin_dir_url( __FILE__ ) . 'js/jquery.timepicker.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->hospitality, plugin_dir_url( __FILE__ ) . 'js/hospitality-admin' . $js_suffix , array( 'jquery' ), $this->version, false );



	}

	private function queue_google_maps() {

		$options = get_option( GUESTABA_HSP_OPTIONS_NAME );

		$api_key = '';

		if ( isset($options['hsp_google_maps_api_key'] )) {
			$api_key = $options['hsp_google_maps_api_key'];
		}


		$google_map_url = '//maps.googleapis.com/maps/api/js?v=3&key=' . $api_key . '&callback=initAdminMap';
		wp_enqueue_script(
			'google-maps',
			$google_map_url,
			array(),
			'1.0',
			true
		);

	}

	public function send_dashboard_notices() {

		// TODO: do check to see if necessary set up has been completed, if not display message

		$option = get_option( GUESTABA_HSP_MESSAGE_OPTIONS_NAME );
		$output = '';
		

		if ($option['upgrade_message_displayed'] == false  ) {

			$message = $option['upgrade_message'];

			$output = '<div id="hsp-admin-notice" class="updated fade"><p>' . $message . '</p>';
			$output .= '<button id="hsp-admin-notice-dismiss">X</button>';
			$output .= '</div>';

			// echo $output;
		}
		
		// Check to see if necesasry objects have been configured and display message if not.

		$message = '';
		if ( !isset( $option['setup_message_displayed'] ) || $option['setup_message_displayed'] == false  ) {

			$message = __('The Hospitality plugin is installed.', GUESTABA_HSP_TEXTDOMAIN);
			$message .= ' <a href="https://www.guestaba.com/knowledgebase/">' . __('See our knowledgebase for setup instructions.', GUESTABA_HSP_TEXTDOMAIN ) . '</a>';


			if ( !empty( $message )) {
				$output = '<div id="hsp-setup-notice" class="updated fade"><p>Hospitality Plugin: ' . $message . '</p>';
				$output .= '<button id="hsp-setup-notice-dismiss">X</button>';
				$output .= '</div>';
			}

		}


		if (!empty( $output )) {
			echo $output;
		}

	}

	/**
	 * Runs wp_localize_script in order to pass localized strings to javascripts.
	 *
	 * @since    1.0.0
	 */
	public function localize_scripts () {

		$wp_js_info = array('site_url' => __(site_url()));
		
		$options = get_option( GUESTABA_HSP_OPTIONS_NAME );

		wp_localize_script( $this->hospitality , 'hsp_admin_objectl10n', array(
			'wpsiteinfo' => $wp_js_info,
			'get_amenity_set_list_error' => __('Error retrieving amenity set list.',  GUESTABA_HSP_TEXTDOMAIN ),
			'get_pricing_model_error' => __('Error retrieving pricing model.',  GUESTABA_HSP_TEXTDOMAIN ),
			'server_error' => __('Server error:', GUESTABA_HSP_TEXTDOMAIN ),
			'note_no_amnenity_set_selected' => __('No amenity set selected.', GUESTABA_HSP_TEXTDOMAIN),
			'note_no_pricing_model_selected' => __('No pricing model selected.', GUESTABA_HSP_TEXTDOMAIN),
			'jan' => __('Jan', GUESTABA_HSP_TEXTDOMAIN),
			'feb' => __('Feb', GUESTABA_HSP_TEXTDOMAIN),
			'mar' => __('Mar', GUESTABA_HSP_TEXTDOMAIN),
			'apr' => __('Apr', GUESTABA_HSP_TEXTDOMAIN),
			'may' => __('May', GUESTABA_HSP_TEXTDOMAIN),
			'jun' => __('Jun', GUESTABA_HSP_TEXTDOMAIN),
			'jul' => __('Jul', GUESTABA_HSP_TEXTDOMAIN),
			'aug' => __('Aug', GUESTABA_HSP_TEXTDOMAIN),
			'sep' => __('Sep', GUESTABA_HSP_TEXTDOMAIN),
			'oct' => __('Oct', GUESTABA_HSP_TEXTDOMAIN),
			'nov' => __('Nov', GUESTABA_HSP_TEXTDOMAIN),
			'dec' => __('Dec', GUESTABA_HSP_TEXTDOMAIN),
			'sunday' => __('Sunday', GUESTABA_HSP_TEXTDOMAIN),
			'monday' => __('Monday', GUESTABA_HSP_TEXTDOMAIN),
			'tuesday' => __('Tuesday', GUESTABA_HSP_TEXTDOMAIN),
			'wednesday' => __('Wednesday', GUESTABA_HSP_TEXTDOMAIN),
			'thursday' => __('Thursday', GUESTABA_HSP_TEXTDOMAIN),
			'friday' => __('Friday', GUESTABA_HSP_TEXTDOMAIN),
			'saturday' => __('Saturday', GUESTABA_HSP_TEXTDOMAIN),
			'gaps_msg' => __('Coverage gaps:', GUESTABA_HSP_TEXTDOMAIN),
			'overlaps_msg' => __('Coverage overlaps:', GUESTABA_HSP_TEXTDOMAIN),
			'geocode_ok' => __('Geocode was successful. Update this post to save the coordinates.', GUESTABA_HSP_TEXTDOMAIN),
			'reservation_conflict' => __('Warning: This change will conflict with another reservation.', GUESTABA_HSP_TEXTDOMAIN ),
			'geocode_error' => __('Geocode failed:', GUESTABA_HSP_TEXTDOMAIN ),
			'current_post_type' => get_post_type(),
			'checkInTime' => $options['hsp_checkin_time']
		));
	}

}
