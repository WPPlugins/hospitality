<?php

/**
 * Created by PhpStorm.
 * User: weskempferjr
 * Date: 11/1/16
 * Time: 11:58 AM
 */
class Hospitality_Demo
{

    private static  $demo_images = array(
                                        'demo_room_1.jpg',
                                        'demo_room_2.jpg',
                                        'demo_room_3.jpg',
                                        'demo_room_4.jpg',
                                        'demo_room_5.jpg',
                                        'demo_room_6.jpg'
                                    );



    /**
     * Hospitality_Demo constructor.
     */
    public function __construct()
    {
    }
    
    public function gen_demo_data() {
        
        $rooms =  Reservation_Agent::get_all_rooms();
        $locations = Locations_Post_Type::get_locations();
        
        $rooms_file_path = plugin_dir_path( dirname( __FILE__ ) ) . 'demo/data/rooms.json';
        $locations_file_path = plugin_dir_path( dirname( __FILE__ ) ) . 'demo/data/locations.json';

        $ret_loc = file_put_contents( $locations_file_path , json_encode($locations));

        $ret_room = file_put_contents( $rooms_file_path , json_encode($rooms));

        if ( $ret_loc > 0 && $ret_room > 0 && $ret_room != false && $ret_loc != false ) {
            return true;
        }

        return false;

        
    }


    public function load_demo_data() {

        try {
            $this->load_demo_rooms();
        }
        catch( Exception $e ) {
            Hospitality_Logger::log_error('Could not load demo data, ' + $e->getMessage() );
            return false;
        }

        return true;

    }


    private function load_demo_locations() {

        $locations_file_path = plugin_dir_path( dirname( __FILE__ ) ) . 'demo/data/locations.json';

        $locations = json_decode(file_get_contents( $locations_file_path ));
        
        $location_ids = array();

        // Add locations
        foreach ( $locations as $location ) {

            $args = array(
                'post_type' => 'locations',
                'post_title' => $location->title ,
                'post_content' => $location->description ,
                'post_status' => 'publish'
            );

            $post_ID = wp_insert_post( $args );

            if ( $post_ID != 0 && !( $post_ID instanceof WP_Error ) ) {

                update_post_meta( $post_ID, 'address1', $location->address1 );
                update_post_meta( $post_ID, 'address2', $location->address2 );
                update_post_meta( $post_ID, 'city', $location->city );
                update_post_meta( $post_ID, 'state', $location->state);
                update_post_meta( $post_ID, 'post_code', $location->post_code );

            }
            else {
                return false ;
            }
            
            $location_ids[] = $post_ID;


        }
        
        return $location_ids;

    }


    private function load_demo_rooms() {

        // return true;

        $location_ids = $this->load_demo_locations();
        if ( !isset( $location_ids) || count( $location_ids) === 0 ) {
            Hospitality_Logger::log_error(__('Failed to load demo locations.',GUESTABA_HSP_TEXTDOMAIN));
            return false;
        }


        $rooms_file_path = plugin_dir_path( dirname( __FILE__ ) ) . 'demo/data/rooms.json';
        $rooms = json_decode(file_get_contents( $rooms_file_path ), true);

        if ( count($rooms) > 0 ) {
            
            
            $pricings = $rooms[0]['pricings'] ;
            // Create pricing model first.
            $pm_id = $this->load_demo_pricing_model( $pricings );

            $amenities = $rooms[0]['amenities'] ;
            $as_id = $this->load_demo_amenity_set( $amenities  );

            $attachment_ids = $this->load_demo_images();

            if ( !isset($attachment_ids) || count( $attachment_ids) === 0 ) {
                Hospitality_Logger::log_error(__('Demo loader failed load images.', GUESTABA_HSP_TEXTDOMAIN));
                return false;
            }

            $count = 0;

            foreach ( $rooms as $room ) {
                
                $args = array(
                    'post_type' => 'rooms',
                    'post_title' => $room['title'] ,
                    'post_content' => $room['room_desc'] ,
                    'post_status' => 'publish'
                );

                $post_ID = wp_insert_post( $args );

                if ( $post_ID != 0 && !( $post_ID instanceof WP_Error ) ) {

                    update_post_meta( $post_ID, 'meta_room_slogan', $room['room_slogan'] );
                    update_post_meta( $post_ID, 'meta_room_desc', $room['room_desc'] );
                    update_post_meta( $post_ID, 'meta_room_excerpt', $room['room_excerpt'] );
                    update_post_meta( $post_ID, 'meta_room_max_occupancy', $room['max_occupancy'] );
                    update_post_meta( $post_ID, 'meta_room_amenity_list_icon', $room['amenity_list_icon'] );
                    
                    update_post_meta( $post_ID, 'meta_room_amenity_select', $as_id );
                    update_post_meta( $post_ID, 'meta_room_pricing_select', $pm_id );
                    
                    update_post_meta( $post_ID, 'meta_room_slider_animation_effect', $room['slider_config']['effect'] );
                    update_post_meta( $post_ID, 'meta_room_alternate_image_shortcode', '' );
                    

                    update_post_meta( $post_ID, 'meta_room_slider_animation_auto', $room['slider_config']['auto_animation'] );
                    update_post_meta( $post_ID, 'meta_room_slider_animation_pause', $room['slider_config']['pause_on_hover'] );
                    
                    update_post_meta( $post_ID, 'meta_room_slider_animation_duration', $room['slider_config']['animation_duration']  );
                    update_post_meta( $post_ID, 'meta_room_slider_animation_speed', $room['slider_config']['slide_duration'] );

                    
                    update_post_meta( $post_ID, 'meta_room_amenity_list', $room['amenities'] );

                    // Assign 2 of 6 demo images as slider images for this room.
                    $base_index = ( $count % 3 ) * 2;
                    $image_id_1 = $attachment_ids[ $base_index  ];
                    $image_id_2 = $attachment_ids[ $base_index + 1 ];

                    $image_url_1 = wp_get_attachment_image_url( $image_id_1, 'large' );
                    $image_url_2 = wp_get_attachment_image_url( $image_id_2, 'large' );
                    
                    $sliders = array(
                            array(
                                'title' =>  __('Demo Slide', GUESTABA_HSP_TEXTDOMAIN),
                                'meta_room_slider_image' => $image_url_1,
                                'meta_room_slider_description' => __('Demo Slide Description', GUESTABA_HSP_TEXTDOMAIN)
                            ),
                            array(
                                'title' =>  __('Demo Slide', GUESTABA_HSP_TEXTDOMAIN),
                                'meta_room_slider_image' => $image_url_2,
                                'meta_room_slider_description' => __('Demo Slide Description', GUESTABA_HSP_TEXTDOMAIN)
                            )
                    );

                    set_post_thumbnail( $post_ID, $image_id_1 );

                    update_post_meta( $post_ID, 'meta_room_slider', $sliders );

                    $location = Locations_Post_Type::get_location( $location_ids[0]);


                    $room_locations = array(
                        array(
                            'location_id' => $location_ids[0],
                            'unit_number' => '10' . $base_index ,
                            'room_id' => $post_ID,
                            'status' => '',
                            'location_title' => $location['title'],
                            'room_title' => $room['title'],
                            'description' => ''
                        ),
                        array(
                            'location_id' => $location_ids[0],
                            'unit_number' => '10' . $base_index + 1  ,
                            'room_id' => $post_ID,
                            'status' => '',
                            'location_title' => $location['title'],
                            'room_title' => $room['title'],
                            'description' => ''
                        )
                    );


                    $room_locations = array(
                        Room_Locations_Post_Type::add_room_location( $room_locations[0]),
                        Room_Locations_Post_Type::add_room_location( $room_locations[1])
                    );
                    
                    if ( !isset($room_locations[0]) || !isset($room_locations[1]))
                    {
                        Hospitality_Logger::log_error('Could not add demo room locations.',GUESTABA_HSP_TEXTDOMAIN);
                    }
                    else {
                        $location_ids = array(
                            $room_locations[0]['id'],
                            $room_locations[1]['id']
                        );
                        
                        update_post_meta( $post_ID, 'meta_room_locations_ids', $location_ids );
                    }
                    
                    
                    
                }
                else {
                    return false ;
                }

                $count++;

            }

        }
        else {
            Hospitality_Logger::log_error('No room data in demo file.');
            return false;
        }

        return true;
    }
    
    private function load_demo_pricing_model( $pricings ) {

        // return true;
        
        $args = array(
            'post_type' => 'pricing-models',
            'post_title' => __('Demo Pricing Model',GUESTABA_HSP_TEXTDOMAIN) ,
            'post_content' => __('Demo Pricing Model', GUESTABA_HSP_TEXTDOMAIN),
            'post_status' => 'publish'
        );

        $post_ID = wp_insert_post( $args );



        if ( $post_ID != 0 && !( $post_ID instanceof WP_Error ) ) {
            update_post_meta( $post_ID, 'meta_pricing_model_list', $pricings );
        }
        else {
            return false;
        }

        return $post_ID;


    }


    private function load_demo_amenity_set( $amentity_set ) {

        // return true;

        $demo_amenities = array(
            array('title' =>'Television', 'icon' => 'television'),
            array('title' =>'Jacuzzi', 'icon' => 'bath'),
            array('title' =>'Wifi', 'icon' => 'wifi'),
            array('title' =>'Coffee', 'icon' => 'coffee')
        );

        $args = array(
            'post_type' => 'amenity-sets',
            'post_title' => __('Demo Amenity Set',GUESTABA_HSP_TEXTDOMAIN) ,
            'post_content' => __('Demo Amenity Set', GUESTABA_HSP_TEXTDOMAIN),
            'post_status' => 'publish'
        );

        $post_ID = wp_insert_post( $args );


        if ( $post_ID != 0 && !( $post_ID instanceof WP_Error ) ) {
            update_post_meta($post_ID, 'meta_amenity_set_desc', __('Demo Amenity Set', GUESTABA_HSP_TEXTDOMAIN));
            update_post_meta( $post_ID, 'meta_amenity_set_list', $demo_amenities );
        }
        else {
            return false;
        }

        return $post_ID;



    }


    private function load_demo_images() {

        $demo_data_dir = plugin_dir_path( dirname( __FILE__ ) ) . 'demo';
        $image_dir = $demo_data_dir . '/images';


        $attachment_ids = array();

        foreach ( self::$demo_images as $image ) {

            $filename = $image_dir . '/' . $image ;
            $upload_file = wp_upload_bits( $image, null, file_get_contents( $filename ));

            if (!$upload_file['error']) {
                $wp_filetype = wp_check_filetype($filename, null );
                $attachment = array(
                    'post_mime_type' => $wp_filetype['type'],
                    'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                    'post_content' => '',
                    'post_status' => 'inherit'
                );
                $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'] );
                if (!is_wp_error($attachment_id)) {
                    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                    $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
                    wp_update_attachment_metadata( $attachment_id,  $attachment_data );
                }

                $attachment_ids[] = $attachment_id;
            }
            else {
                return false;
            }
        }

        return $attachment_ids;

    }
}