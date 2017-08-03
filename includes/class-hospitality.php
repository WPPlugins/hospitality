<?php


/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Hospitality
 * @subpackage Hospitality/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Hospitality
 * @subpackage Hospitality/includes
 * @author     Wes Kempfer <wkempferjr@tnotw.com>
 */
class Hospitality {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Hospitality_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $hospitality    The string used to uniquely identify this plugin.
	 */
	protected $hospitality;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
	
	
	/**
	 * Stores the name of the rooms post type.
	 * @since   1.0.0
	 * @access 	private
	 * @var		Rooms_Post_Type $rooms_post_type  The rooms post type instance. 
	 */
	protected $rooms_post_type ;
	
	/**
	 * Stores name of amenity sets post type.
	 * @since   1.0.0
	 * @access 	private
	 * @var		Archive_Sets_Post_Type $rooms_post_type  The rooms post type instance. 
	 */
	protected $amenity_sets_post_type ;
	
	/**
	 * Stores name of pricing models post type.
	 * @since   1.0.0
	 * @access 	private
	 * @var		Pricing_Models_Post_Type $rooms_post_type  The rooms post type instance. 
	 */
	protected $pricing_models_post_type ;

	/**
	 * Stores name of room locations post type.
	 * @since   1.0.5
	 * @access 	private
	 * @var		Room_Locations_Post_Type $room_locations_post_type  The rooms post type instance.
	 */
	protected $room_locations_post_type ;

	/**
	 * Stores name of reservations post type.
	 * @since   1.0.5
	 * @access 	private
	 * @var		Reservations_Post_Type $reservations_post_type  The rooms post type instance.
	 */
	protected $reservations_post_type ;

	/**
	 * Stores name of bookings post type.
	 * @since   1.0.5
	 * @access 	private
	 * @var		Locations_Post_Type $locations_post_type  The rooms post type instance.
	 */
	protected $locations_post_type ;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */



	public function __construct() {

		$this->hospitality = GUESTABA_HSP_TEXTDOMAIN;
		$this->version = GUESTABA_HOSPITALITY_VERSION_NUM;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->load_customizer();
		$this->define_public_hooks();
		$this->register_post_types();
		$this->register_shortcodes();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Hospitality_Loader. Orchestrates the hooks of the plugin.
	 * - Hospitality_i18n. Defines internationalization functionality.
	 * - Hospitality_Admin. Defines all hooks for the dashboard.
	 * - Hospitality_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		
		/**
		 * Load constants
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'constants.php';
        
        /*
         * Payment gateway
         */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/paygates/interface-payment-gateway.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/paygates/class-payment-gateway-factory.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/paygates/class-paypal-express-payment-gateway.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/paygates/class-offline-payment-gateway.php';


		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/CountryState.php';




		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hospitality-logger.php';
		
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hospitality-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hospitality-i18n.php';


		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-hospitality-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-hospitality-public.php';
		
		/**
		 * The class responsible for defining rooms custom post type
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'model/class-rooms-post-type.php';
		
		/**
		 * The class responsible for defining amenity sets custom post type
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'model/class-amenity-sets-post-type.php';
		
		/**
		 * The class responsible for defining pricing models custom post type
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'model/class-pricing-models-post-type.php';



		/**
		 * The class responsible for defining room locations custom post type
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'model/class-room-locations-post-type.php';


		/**
		 * The class responsible for defining reservations custom post type
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'model/class-reservations-post-type.php';

		/**
		 * The class responsible for defining locations custom post type
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'model/class-locations-post-type.php';





		/**
		 * Class file for admin/settings page
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hospitality-settings.php';

		/**
		 * Class files for post type meta boxes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hospitality-meta-box.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hospitality-rooms-meta-box.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hospitality-pricing-models-meta-box.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hospitality-amenity-sets-meta-box.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hospitality-reservations-meta-box.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hospitality-locations-meta-box.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hospitality-room-locations-meta-box.php';



		/*
		 * Menu manager
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hospitality-menu-manager.php';
		
		
		/*
		 * User meta manager handles extra user meta fields -- addresss, phone, etc. 
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hospitality-user-meta-manager.php';




		/**
		 * The class responsible defining and adding shortcodes. 
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hospitality-shortcodes.php';
			
		$this->loader = new Hospitality_Loader();
		$this->rooms_post_type = new Rooms_Post_Type();
		$this->amenity_sets_post_type = new Amenity_Sets_Post_Type() ;
		$this->pricing_models_post_type = new Pricing_Models_Post_Type();
		$this->room_locations_post_type = new Room_Locations_Post_Type();
		$this->reservations_post_type = new Reservations_Post_Type();
		$this->locations_post_type = new Locations_Post_Type();


		/**
		 * The classes responsible for handling ajax requests
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hospitality-ajax.php';
		
		/*
		 * Payment gateways
		 */


		/**
		 * The page manager creates pages that are used to display hospitality objects
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hospitality-page-manager.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hospitality-reservation-agent.php';


		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hospitality-demo.php';



	}

	/**
	 * Load the Option Tree-based customizer. Test files cannot be exected until 
	 * the filters are run that specify configuration options. Run immediately 
	 * after load_admin_hooks().
	 * 
	 * @since 1.0
	 * @access private
	 * @var none
	 */
	private function load_customizer( ) {

	}
	
	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Hospitality_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Hospitality_i18n();
		$plugin_i18n->set_domain( $this->get_hospitality() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		global $post ;

		$this->loader->add_action('plugins_loaded', $this, 'upgrade_data');

		$plugin_admin = new Hospitality_Admin( $this->get_hospitality(), $this->get_version() );
		

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'localize_scripts');
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'send_dashboard_notices');

		$this->loader->add_action( 'views_edit-rooms', $this->rooms_post_type, 'remove_post_actions' );
		$this->loader->add_filter( 'page_row_actions', $this->rooms_post_type, 'remove_post_actions' );
		$this->loader->add_action('parse_query',$this->rooms_post_type,'query_my_post_types' );
		$this->loader->add_action('pre_get_posts',$this->rooms_post_type,'add_to_query' );
		
		
		$this->loader->add_action( 'views_edit-amenity-sets', $this->amenity_sets_post_type, 'remove_post_actions' );
		$this->loader->add_filter( 'page_row_actions', $this->amenity_sets_post_type, 'remove_post_actions' );
		$this->loader->add_action('parse_query',$this->amenity_sets_post_type,'query_my_post_types' );
		$this->loader->add_action('pre_get_posts',$this->amenity_sets_post_type,'add_to_query' );

		$this->loader->add_action( 'views_edit-reservations', $this->reservations_post_type, 'remove_post_actions' );
		$this->loader->add_filter( 'page_row_actions', $this->reservations_post_type, 'remove_post_actions' );
		$this->loader->add_action('parse_query',$this->reservations_post_type,'query_my_post_types' );
		$this->loader->add_action('pre_get_posts',$this->reservations_post_type,'add_to_query' );


		$this->loader->add_action( 'views_edit-locations', $this->locations_post_type, 'remove_post_actions' );
		$this->loader->add_filter( 'page_row_actions', $this->locations_post_type, 'remove_post_actions' );
		$this->loader->add_action('parse_query',$this->locations_post_type,'query_my_post_types' );
		$this->loader->add_action('pre_get_posts',$this->locations_post_type,'add_to_query' );




		$hsp_settings = new Hospitality_Settings();
		$this->loader->add_action('admin_menu', $hsp_settings, 'add_hsp_options_page');
		$this->loader->add_action('admin_init', $hsp_settings, 'settings_init');
		$this->loader->add_action('plugin_action_links_' . GUESTABA_HSP_PLUGIN_FILE, $hsp_settings, 'action_links');

		$hsp_user_meta_manager = new Hospitality_User_Meta_Manager();
		$this->loader->add_action('show_user_profile', $hsp_user_meta_manager, 'render_extra_profile_fields');
		$this->loader->add_action('edit_user_profile', $hsp_user_meta_manager, 'render_extra_profile_fields');
		$this->loader->add_action('personal_options_update', $hsp_user_meta_manager, 'save_extra_profile_fields');
		$this->loader->add_action('edit_user_profile_update', $hsp_user_meta_manager, 'save_extra_profile_fields');





		/*
		 *  For future use:
		 *  $hsp_menu_manager = new Hospitality_Menu_Manager();
		 * $this->loader->add_action('admin_menu', $hsp_menu_manager, 'configure_menu');
		*/
		

		$hsp_page_manager = new Hospitality_Page_Manager();

		$this->loader->add_action('init', $hsp_page_manager, 'create_rooms_listing_page');
		$this->loader->add_action('init', $hsp_page_manager, 'create_room_detail_page');
		$this->loader->add_action('init', $hsp_page_manager, 'add_rewrite_tags');
		$this->loader->add_action('init', $hsp_page_manager, 'add_rewrite_rules');
		$this->loader->add_filter('the_content', $hsp_page_manager, 'display_rooms_list_page');
		$this->loader->add_filter('the_content', $hsp_page_manager, 'display_room_detail_page');
		$this->loader->add_filter('query_vars', $hsp_page_manager, 'add_query_vars');

		switch ( $this->get_current_post_type() ) {
			case 'rooms':
			case 'edit-rooms':
				$rooms_meta_box = new Hospitality_Rooms_Meta_Box();
				$this->loader->add_action( 'add_meta_boxes', $rooms_meta_box, 'meta_box_init' );
				$this->loader->add_action( 'admin_menu', $rooms_meta_box, 'remove_meta_boxes' );
				$this->loader->add_action( 'save_post', $rooms_meta_box, 'post_meta_save' );
				break;

			case 'pricing-models':
			case 'edit-pricing-models':
				$pricing_models_meta_box = new Hospitality_Pricing_Models_Meta_Box();
				$this->loader->add_action( 'add_meta_boxes', $pricing_models_meta_box, 'meta_box_init' );
				$this->loader->add_action( 'admin_menu', $pricing_models_meta_box, 'remove_meta_boxes' );
				$this->loader->add_action( 'save_post', $pricing_models_meta_box, 'post_meta_save' );
				break;

			case 'amenity-sets':
			case 'edit-amenity-sets':
				$amenity_sets_meta_box = new Hospitality_Amenity_Sets_Meta_Box();
				$this->loader->add_action( 'add_meta_boxes', $amenity_sets_meta_box, 'meta_box_init' );
				$this->loader->add_action( 'admin_menu', $amenity_sets_meta_box, 'remove_meta_boxes' );
				$this->loader->add_action( 'save_post', $amenity_sets_meta_box, 'post_meta_save' );
				break;


			case 'reservations':
			case 'edit-reservations':
				$reservations_meta_box = new Hospitality_Reservations_Meta_Box();
				$this->loader->add_action( 'add_meta_boxes', $reservations_meta_box, 'meta_box_init' );
				$this->loader->add_action( 'admin_menu', $reservations_meta_box, 'remove_meta_boxes' );
				$this->loader->add_action( 'save_post', $reservations_meta_box, 'post_meta_save' );


				$reservations_cpt = new Reservations_Post_Type();
				$this->loader->add_filter('manage_edit-reservations_columns', $reservations_cpt, 'init_custom_columns') ;
				$this->loader->add_action( 'manage_reservations_posts_custom_column', $reservations_cpt, 'output_custom_columns' );

				$this->loader->add_filter('manage_edit-reservations_sortable_columns', $reservations_cpt, 'init_sortable_columns') ;
				$this->loader->add_action( 'load-edit.php', $reservations_cpt, 'init_edit_sort' );

				$this->loader->add_action( 'restrict_manage_posts', $reservations_cpt, 'add_active_status_filter_to_admin' );
				$this->loader->add_action( 'pre_get_posts', $reservations_cpt, 'filter_by_active_status' );

				$this->loader->add_action( 'restrict_manage_posts', $reservations_cpt, 'add_payment_status_filter_to_admin' );
				$this->loader->add_action( 'pre_get_posts', $reservations_cpt, 'filter_by_payment_status' );
                

			break;

			case 'locations':
			case 'edit-locations':
				$locations_meta_box = new Hospitality_Locations_Meta_Box();
				$this->loader->add_action( 'add_meta_boxes', $locations_meta_box, 'meta_box_init' );
				$this->loader->add_action( 'admin_menu', $locations_meta_box, 'remove_meta_boxes' );
				$this->loader->add_action( 'save_post', $locations_meta_box, 'post_meta_save' );
				break;

			case 'room-locations':
			case 'edit-room-locations':
				$room_locations_meta_box = new Hospitality_Room_Locations_Meta_Box();
				$this->loader->add_action( 'add_meta_boxes', $room_locations_meta_box, 'meta_box_init' );
				$this->loader->add_action( 'admin_menu', $room_locations_meta_box, 'remove_meta_boxes' );
				$this->loader->add_action( 'save_post', $room_locations_meta_box, 'post_meta_save' );
				break;



			default:
				break;
		}


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Hospitality_Public( $this->get_hospitality(), $this->get_version() );
		
		$ajax_controller = new Hospitality_Public_Ajax_Controller();
		
		

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'localize_scripts');
		$this->loader->add_action( 'widgets_init', $plugin_public, 'register_widget_areas' ); 
	
	
		$this->loader->add_action('wp_ajax_nopriv_hospitality_ajax' , $ajax_controller, 'hospitality_ajax');
		$this->loader->add_action('wp_ajax_hospitality_ajax' , $ajax_controller, 'hospitality_ajax');
		$this->loader->add_action('wp_ajax_nopriv_ajaxlogin' , $ajax_controller, 'ajax_login');


		
		$this->loader->add_filter( 'single_template', $this->rooms_post_type, 'get_custom_post_type_template' ); 
		$this->loader->add_filter( 'archive_template', $this->rooms_post_type, 'get_custom_post_type_template' );






	}
	/**
	 * Register custom post types
	 * @since 	1.0.0
	 * @access 	private
	 */
	private function register_post_types() {
		$this->loader->add_action('init', $this->rooms_post_type, 'register');
		$this->loader->add_action('init', $this->amenity_sets_post_type, 'register');
		$this->loader->add_action('init', $this->pricing_models_post_type, 'register');
		$this->loader->add_action('init', $this->room_locations_post_type, 'register');
		$this->loader->add_action('init', $this->reservations_post_type, 'register');
		$this->loader->add_action('init', $this->locations_post_type, 'register');

	}

	
	/**
	 * Register shortcodes
	 */
	private function register_shortcodes() {
		$shortcodes = new Hospitality_Shortcodes();
		$shortcodes->register_shortcodes();
	}

	
	
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_hospitality() {
		return $this->hospitality;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Hospitality_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


	private function get_current_post_type() {
		if ( isset( $_REQUEST['post_type'] )  ) {
			return $_REQUEST['post_type'];
		}
		elseif (isset( $_REQUEST['screen_id'] ) ) {
			return $_REQUEST['screen_id'];
		}
		elseif (isset( $_POST['screen_id'] ) ) {
			return $_POST['screen_id'];
		}
		else {
			if ( isset( $_REQUEST['post'])) {
				$post_type = get_post_type( $_REQUEST['post'] );
				return $post_type;
			}
		}
	}

	/**
	 * Function: update_meta_data
	 *
	 * This function, if necessary, updates the meta data structure if it detects that the plugin
	 * has been updated by comparing the plugin's current version number with that saved in options.
	 *
	 *
	 * @param none
	 * @return void
	 *
	 * @since 1.0.3
	 *
	 */
	public function upgrade_data() {

		if ( current_user_can( 'activate_plugins' ) ) {
			

			
			$option = get_option( GUESTABA_HSP_OPTIONS_NAME );
			
			if (!isset( $option['version']) || version_compare( $option['version'], $this->version)  < 0 ) {
				$option['version'] = $this->version;

			}
			
			if ( !isset( $option['version']) || version_compare( $option['version'], $this->version)  < 0 ) {
				$message_option = array(
					'upgrade_message' => sprintf(__('Hospitality %s is installed.', GUESTABA_HSP_TEXTDOMAIN ), GUESTABA_HOSPITALITY_VERSION_NUM),
					'upgrade_message_displayed' => true
				);
				// update option table
				if ( ! update_option( GUESTABA_HSP_MESSAGE_OPTIONS_NAME, $message_option ) ) {
					error_log('Error updating message options, ' . __FILE__ . ':' . __LINE__);
				}
				
				// update option table
				if ( ! update_option( GUESTABA_HSP_OPTIONS_NAME, $option ) ) {
					error_log('Error updating options: ' . __FILE__ . ':' . __LINE__);
				}

				$this->loader->add_action('init', $this, 'upgrade_meta_data');

			}
		}
	}

	/**
	 * Function: upgrade_meta_data()
	 *
	 * Upgrades meta data structure as required by new features and other code modifications.
	 *
	 * @since 1.0.3
	 *
	 * @param none
	 * @return void
	 *
	 *
	 */
	public function upgrade_meta_data() {

		// Meta data updates
		// 1.0.3 updates
		$args = array(
			'post_type'      => 'pricing-models',
			'posts_per_page' => - 1
		);

		$rm_query = new WP_Query( $args );
		while ( $rm_query->have_posts() ) : $rm_query->the_post();
			$room_pricings = get_post_meta( get_the_ID(), 'meta_pricing_model_list', true );
			$upg_room_pricings = array();
			foreach ( $room_pricings as $room_pricing ){
				if ( ! isset( $room_pricing['dow_price'] ) ) {
					$room_pricing['dow_price'] = array(
						'sunday'    => $room_pricing['meta_room_price'],
						'monday'    => $room_pricing['meta_room_price'],
						'tuesday'   => $room_pricing['meta_room_price'],
						'wednesday' => $room_pricing['meta_room_price'],
						'thursday'  => $room_pricing['meta_room_price'],
						'friday'    => $room_pricing['meta_room_price'],
						'saturday'  => $room_pricing['meta_room_price']
					);

				}
				$upg_room_pricings[] = $room_pricing;
			}


			update_post_meta( get_the_ID(), 'meta_pricing_model_list', $upg_room_pricings);

		endwhile;

	}

	public function create_default_objects() {
		

		if ( current_user_can('activate_plugins')) {

			$options = get_option(GUESTABA_HSP_OPTIONS_NAME);

			if ( !isset( $options['demo_data_loaded'] )  ||  $options['demo_data_loaded']  == false  ) {
				$demo_loader = new Hospitality_Demo();
				$demo_loader->load_demo_data();
				$options['demo_data_loaded'] = true;

                // Default settings
                $options['hsp_searchable_amenities'] = 'Wifi, Television, Ocean View, Jacuzzi, Microwave';
			}


			update_option( GUESTABA_HSP_OPTIONS_NAME, $options );

		}

	}





}
