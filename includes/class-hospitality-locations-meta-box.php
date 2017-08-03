<?php

/**
 *
 */
class Hospitality_Locations_Meta_Box extends Hospitality_Meta_Box {


	/**
	 * Hospitality_Locations_Meta_Box constructor.
	 */
	public function __construct() {
		$this->setPostType( 'locations' );
		$this->setMetaBoxID(  'locations_cpt_meta_box' );
		$this->setMetaBoxTitle(  __( 'Locations Display Options', GUESTABA_HSP_TEXTDOMAIN ) );
		$this->setNonceId( 'locations_mb_nonce');
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
		remove_meta_box('revisionsdiv', 'locations', 'norm');
		remove_meta_box('slugdiv', 'locations', 'norm');
		remove_meta_box('authordiv', 'locations', 'norm');
		remove_meta_box('postcustom', 'locations', 'norm');
		remove_meta_box('postexcerpt', 'locations', 'norm');
		remove_meta_box('trackbacksdiv', 'locations', 'norm');
		remove_meta_box('commentsdiv', 'locations', 'norm');
		remove_meta_box('pageparentdiv', 'locations', 'norm');
		remove_meta_box('commentstatusdiv', 'locations', 'norm');

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

		$this->section_heading(__('Location Address', GUESTABA_HSP_TEXTDOMAIN), 'gst-mb-location-address');

		$this->text_input( __('Name', GUESTABA_HSP_TEXTDOMAIN),
			get_post_meta( $post_ID, 'name', true),
			'name'
		);

		$this->text_input( __('Address 1', GUESTABA_HSP_TEXTDOMAIN),
			get_post_meta( $post_ID, 'address1', true),
			'address1'
		);

		$this->text_input( __('Address 2', GUESTABA_HSP_TEXTDOMAIN),
			get_post_meta( $post_ID, 'address2', true),
			'address2'
		);

		$this->text_input( __('City', GUESTABA_HSP_TEXTDOMAIN),
			get_post_meta( $post_ID, 'city', true),
			'city'
		);

		// TDOD: contraint state input to list. (drop down list)
		$this->text_input( __('State', GUESTABA_HSP_TEXTDOMAIN),
			get_post_meta( $post_ID, 'state', true),
			'state'
		);

		$this->text_input( __('Postal Code', GUESTABA_HSP_TEXTDOMAIN),
			get_post_meta( $post_ID, 'postal_code', true),
			'postal_code'
		);


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
		$this->update_meta_text( $post_id, 'name');
		$this->update_meta_text( $post_id, 'address1');
		$this->update_meta_text( $post_id, 'address2');
		$this->update_meta_text( $post_id, 'city');
		$this->update_meta_text( $post_id, 'state');
		$this->update_meta_text( $post_id, 'postal_code');


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
