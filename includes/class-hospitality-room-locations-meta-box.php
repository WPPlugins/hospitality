<?php

/**
 * Created by PhpStorm.
 * User: weskempferjr
 * Date: 10/13/16
 * Time: 7:15 PM
 */
class Hospitality_Room_Locations_Meta_Box extends Hospitality_Meta_Box
{


    /**
     * Hospitality_Locations_Meta_Box constructor.
     */
    public function __construct() {
        $this->setPostType( 'room-locations' );
        $this->setMetaBoxID(  'room_locations_cpt_meta_box' );
        $this->setMetaBoxTitle(  __( 'Room Locations Display Options', GUESTABA_HSP_TEXTDOMAIN ) );
        $this->setNonceId( 'room_locations_mb_nonce');
        $this->init_tooltips();
    }

    /**
     * Function remove_meta_boxes
     *
     * Removes other metaboxes on the dashboard that are not pertinent to the locations custom post type.
     *
     * @param none
     * @return void
     */
    public function remove_meta_boxes () {
        remove_meta_box('revisionsdiv', 'room-locations', 'norm');
        remove_meta_box('slugdiv', 'room-locations', 'norm');
        remove_meta_box('authordiv', 'room-locations', 'norm');
        remove_meta_box('postcustom', 'room-locations', 'norm');
        remove_meta_box('postexcerpt', 'room-locations', 'norm');
        remove_meta_box('trackbacksdiv', 'room-locations', 'norm');
        remove_meta_box('commentsdiv', 'room-locations', 'norm');
        remove_meta_box('pageparentdiv', 'room-locations', 'norm');
        remove_meta_box('commentstatusdiv', 'room-locations', 'norm');

    }

    /**
     * Function meta_box_render
     *
     * This is the render callback function for the locations CPT metabox.
     *
     * @param none
     * @return void
     */
    public function meta_box_render( ) {
        global $post ;


        wp_nonce_field( basename( __FILE__ ), $this->getNonceId() );

        $post_ID = $post->ID;

        /**
         *
         * Content settings section
         */
        echo '<div class="gst_settings_container">';

        $this->section_heading(__('Room Location Information', GUESTABA_HSP_TEXTDOMAIN), 'gst-mb-location-address');


        $this->post_select( __('Facility Location', GUESTABA_HSP_TEXTDOMAIN ),
            get_post_meta($post_ID, 'location_id', true ),
            'location_id',
            'locations'
        );
        
        $this->text_input( __('Unit Number', GUESTABA_HSP_TEXTDOMAIN),
            get_post_meta( $post_ID, 'unit_number', true),
            'unit_number'
        );

        $this->text_input( __('Status', GUESTABA_HSP_TEXTDOMAIN),
            get_post_meta( $post_ID, 'status', true),
            'city'
        );

        echo '<p>Room: ' .  get_post_meta( $post_ID, 'room_title', true) . '</p>';
        

        echo '</div>';


    }


    /**
     * Function post_meta_save
     *
     * This is  post meta data save callback function.
     *
     * @param integer $post_id the post ID for the submitted meta data.
     */
    public function post_meta_save( $post_id ) {
        // Checks save status
        $is_autosave = wp_is_post_autosave( $post_id );
        $is_revision = wp_is_post_revision( $post_id );
        $is_valid_nonce = ( isset( $_POST[ $this->getNonceId()] ) && wp_verify_nonce( $_POST[ $this->getNonceId() ], basename( __FILE__ ) ) ) ? 'true' : 'false';

        // Exits script depending on save status
        if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
            return;
        }
        $this->update_meta_text( $post_id, 'location_id');
        $this->update_meta_text( $post_id, 'unit_number');
        $this->update_meta_text( $post_id, 'status');


    }
    /*
     * Function init_tooltips
     *
     * This function initializes the tooltips for the UI elements of this metabox.
     *
     * @param none
     *
     * @return void
     */
    protected function init_tooltips() {

        $tooltips = array(
            'add_button'          => __( 'Click this button to add a new item to this list.', GUESTABA_HSP_TEXTDOMAIN ),
            'edit_image_button'   => __( 'Click this button select or upload a different image. For best results, choose images 600 px wide by 150 px high.', GUESTABA_HSP_TEXTDOMAIN ),
            'delete_image_button' => __( 'Click this button to remove this image from the slider.', GUESTABA_HSP_TEXTDOMAIN ),
            'delete_text_button'  => __( 'Click this button to remove this item from the list.', GUESTABA_HSP_TEXTDOMAIN ),

        );

        $this->set_tooltips( $tooltips );

    }

}