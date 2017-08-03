<?php
/**
 * Class Hospitality_Rooms_Meta_Box
 *
 * This class defines the appearance and behavior of the metbox associated with
 * the pricing model custom post type.
 */

class Hospitality_Pricing_Models_Meta_Box extends Hospitality_Meta_Box {


	public function __construct() {
		$this->setPostType( 'pricing-models' );
		$this->setMetaBoxID(  'pricing_models_cpt_meta_box' );
		$this->setMetaBoxTitle(  __( 'Pricing Models Options', GUESTABA_HSP_TEXTDOMAIN ) );
		$this->setNonceId( 'pricing_models_mb_nonce');
		$this->init_tooltips();
	}

	/**
	 * Function meta_box_render
	 *
	 * This is the render callback function for the pricing models CPT metabox.
	 *
	 * @param none
	 * @return void
	 */

	public function meta_box_render() {
		global $post ;

		wp_nonce_field( basename( __FILE__ ), $this->getNonceId() );
		$post_ID = $post->ID;

		$enq_media_args = array( 'post' => $post_ID );
		wp_enqueue_media( $enq_media_args );


		echo '<div class="gst_settings_container">';

		$this->section_heading(__('Pricing Model Settings', GUESTABA_HSP_TEXTDOMAIN), 'gst-mb-content-settings');

		$this->text_area(   __('Description',
							GUESTABA_HSP_TEXTDOMAIN) ,
							get_post_meta( $post_ID, 'meta_pricing_model_desc', true),
							10,
							40,
							'meta_pricing_model_desc'
						);

		$pricing_item_labels = array( 'title' => 'Title',
		                            'date_start' => __('Start Date', GUESTABA_HSP_TEXTDOMAIN),
									'date_end' => __('End Date', GUESTABA_HSP_TEXTDOMAIN),
		                            'meta_room_price' => __('Price (by day of the week)', GUESTABA_HSP_TEXTDOMAIN),
									'sunday' => __('S', GUESTABA_HSP_TEXTDOMAIN),
									'monday' => __('M', GUESTABA_HSP_TEXTDOMAIN),
									'tuesday'=> __('T', GUESTABA_HSP_TEXTDOMAIN),
									'wednesday' => __('W', GUESTABA_HSP_TEXTDOMAIN),
									'thursday' => __('Th', GUESTABA_HSP_TEXTDOMAIN),
									'friday' => __('F', GUESTABA_HSP_TEXTDOMAIN),
									'saturday' => __('Sa', GUESTABA_HSP_TEXTDOMAIN)
									);

		$this->sortable_editable_list( __('Pricing', GUESTABA_HSP_TEXTDOMAIN),
										$this->get_pricing_model_list( $post_ID ),
										'meta_pricing_model_list',
										array( $this, 'item_as_pricing_model_dates'),
										'pricing-edit-list',
										$pricing_item_labels,
										'gst-sort-edit-pricing-add',
										true
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

		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ $this->getNonceId()] ) && wp_verify_nonce( $_POST[ $this->getNonceId() ], basename( __FILE__ ) ) ) ? 'true' : 'false';

		// Exits script depending on save status
		if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
			return;
		}


		$this->update_meta_text( $post_id, 'meta_pricing_model_desc');
		$this->update_pricing_model_array( $post_id );


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
			'add_button' => __( 'Click this button to add a new item to this list.', GUESTABA_HSP_TEXTDOMAIN ),
			'meta_pricing_model_list' => __('For each price panel, enter date ranges during which a price will be in effect. Enter a price only for Sunday to set the price the same for all days of the week. Click the + button below to add a new price panel.', GUESTABA_HSP_TEXTDOMAIN),
			'delete_pricing_button' => __('Click here to delete this pricing panel.', GUESTABA_HSP_TEXTDOMAIN)
		);
		$this->set_tooltips( $tooltips );
	}


	/**
	 * Function remove_meta_boxes
	 *
	 * Removes other metaboxes on the dashboard that are not pertinent to the pricing models custom post type.
	 *
	 * @param none
	 * @return void
	 */
	public function remove_meta_boxes () {
		remove_meta_box('revisionsdiv', 'pricing-models', 'norm');
		remove_meta_box('slugdiv', 'pricing-models', 'norm');
		remove_meta_box('authordiv', 'pricing-models', 'norm');
		remove_meta_box('postcustom', 'pricing-models', 'norm');
		remove_meta_box('postexcerpt', 'pricing-models', 'norm');
		remove_meta_box('trackbacksdiv', 'pricing-models', 'norm');
		remove_meta_box('commentsdiv', 'pricing-models', 'norm');
		remove_meta_box('pageparentdiv', 'pricing-models', 'norm');
		remove_meta_box('commentstatusdiv', 'pricing-models', 'norm');

	}


	/**
	 *
	 * Function item_as_pricing_model_dates
	 *
	 * This is an item callback function for sortable_editable_list(). It returns a string containing the
	 * HTML elements for the presentation of the pricing model data entry form.
	 **
	 * @param string $name the name of the individual form element for the item.
	 * @param string $class a user defined class.
	 * @param mixed $item the post meta data array element.
	 *
	 * @return string
	 */
	public function item_as_pricing_model_dates(  $name, $class, $item, $labels ) {


		$input_tag = '<div id="' . $name . '" class="gst-sort-edit-pricing gst_input_group">';

		$input_tag .= '<button title="' . $this->get_tooltip('delete_pricing_button') . '" class="gst-sort-edit-delete" id="' . $name . '-button" formaction="javascript:void(0)">'  . __('X', GUESTABA_HSP_TEXTDOMAIN ) . '</button>';


		$input_tag .= '<br/><label class="gst_pricing_input_label gst_pricing_input_label_title" for="' . $name . '[title]" >' . $labels['title'] . '</label>';
		$input_tag .= '<input type="text"  name="'. $name . '[title]" class="gst_input_group_item gst_clear_input_target gst_price_title_input gst_pricing_input ' . $class . '" value="' . $item['title'] . '">';

		$input_tag .= '<br/><label class="gst_pricing_input_label gst_pricing_input_label_pricing_date" for="' . $name . '[meta_room_pricing_date01][date_start]" >' . $labels['date_start'] . '</label>';
		$input_tag .= '<input type="text"  name="'. $name . '[meta_room_pricing_date01][date_start]" class="gst_input_group_item gst_date_input gst_pricing_input gst_start_date ' . $class . '" value="' . $item['meta_room_pricing_date01']['date_start'] . '">';

		$input_tag .= '<label class="gst_pricing_input_label gst_pricing_input_label_pricing_date" for="' . $name . '[meta_room_pricing_date01][date_end]" >' . $labels['date_end'] . '</label>';
		$input_tag .= '<input type="text"  name="'. $name . '[meta_room_pricing_date01][date_end]" class="gst_input_group_item gst_date_input gst_pricing_input gst_end_date ' . $class . '" value="' . $item['meta_room_pricing_date01']['date_end'] . '">';

		$input_tag .= '<br/><label class="gst_pricing_input_label gst_pricing_input_label_price" >' . $labels['meta_room_price'] . '</label></br>';
		// $input_tag .= '<input type="text"  name="'. $name . '[meta_room_price]" class="gst_input_group_item gst_clear_input_target gst_price_input gst_pricing_input ' . $class . '" value="' . $item['meta_room_price'] . '">';

		$input_tag .= '<label class="gst_pricing_input_label gst_pricing_input_label_price" for="' . $name . '[dow_price][sunday]" >' . $labels['sunday'] . '</label>';
		$input_tag .= '<input type="text"  name="'. $name . '[dow_price][sunday]" class="gst_input_group_item gst_clear_input_target gst_price_input gst_pricing_input ' . $class . '" value="' . $item['dow_price']['sunday'] . '">';

		$input_tag .= '<label class="gst_pricing_input_label gst_pricing_input_label_price" for="' . $name . '[dow_price][monday]" >' . $labels['monday'] . '</label>';
		$input_tag .= '<input type="text"  name="'. $name . '[dow_price][monday]" class="gst_input_group_item gst_clear_input_target gst_price_input gst_pricing_input ' . $class . '" value="' . $item['dow_price']['monday'] . '">';

		$input_tag .= '<label class="gst_pricing_input_label gst_pricing_input_label_price" for="' . $name . '[dow_price][tuesday]" >' . $labels['tuesday'] . '</label>';
		$input_tag .= '<input type="text"  name="'. $name . '[dow_price][tuesday]" class="gst_input_group_item gst_clear_input_target gst_price_input gst_pricing_input ' . $class . '" value="' . $item['dow_price']['tuesday'] . '">';

		$input_tag .= '<label class="gst_pricing_input_label gst_pricing_input_label_price" for="' . $name . '[dow_price][wednesday]" >' . $labels['wednesday'] . '</label>';
		$input_tag .= '<input type="text"  name="'. $name . '[dow_price][wednesday]" class="gst_input_group_item gst_clear_input_target gst_price_input gst_pricing_input ' . $class . '" value="' . $item['dow_price']['wednesday'] . '">';

		$input_tag .= '<label class="gst_pricing_input_label gst_pricing_input_label_price" for="' . $name . '[dow_price][thursday]" >' . $labels['thursday'] . '</label>';
		$input_tag .= '<input type="text"  name="'. $name . '[dow_price][thursday]" class="gst_input_group_item gst_clear_input_target gst_price_input gst_pricing_input ' . $class . '" value="' . $item['dow_price']['thursday'] . '">';

		$input_tag .= '<label class="gst_pricing_input_label gst_pricing_input_label_price" for="' . $name . '[dow_price][friday]" >' . $labels['friday'] . '</label>';
		$input_tag .= '<input type="text"  name="'. $name . '[dow_price][friday]" class="gst_input_group_item gst_clear_input_target gst_price_input gst_pricing_input ' . $class . '" value="' . $item['dow_price']['friday'] . '">';

		$input_tag .= '<label class="gst_pricing_input_label gst_pricing_input_label_price" for="' . $name . '[dow_price][saturday]" >' . $labels['saturday'] . '</label>';
		$input_tag .= '<input type="text"  name="'. $name . '[dow_price][saturday]" class="gst_input_group_item gst_clear_input_target gst_price_input gst_pricing_input ' . $class . '" value="' . $item['dow_price']['saturday'] . '">';



		$input_tag .= '</div>';
		return $input_tag;
	}


	/*
	 * Function update_pricing_model_array
	 *
	 * This function is called by post_meta_save in order to save pricing model data.
	 *
	 * @access private
	 *
	 * @param integer $post_id the post ID for the submitted pricing model post.
	 *
	 * @return void
	 */
	private function update_pricing_model_array( $post_id ) {

		if ( isset( $_POST['meta_pricing_model_list'] ) ) {

			$updates = $_POST['meta_pricing_model_list'];

			$clean_updates = array();
			$idx = 0;
			foreach ( $updates as $update ) {
				$clean_updates[] = array(
					'title'                    => sanitize_text_field( $update['title'] ),
					'meta_room_pricing_date01' =>
						array(
							'date_start' => sanitize_text_field( $update['meta_room_pricing_date01']['date_start'] ),
							'date_end'   => sanitize_text_field( $update['meta_room_pricing_date01']['date_end'] )
						),
					'dow_price' => array(
							'sunday' => absint( $update['dow_price']['sunday']),
							'monday' => absint( $update['dow_price']['monday']),
							'tuesday' => absint( $update['dow_price']['tuesday']),
							'wednesday' => absint( $update['dow_price']['wednesday']),
							'thursday' => absint( $update['dow_price']['thursday']),
							'friday' => absint( $update['dow_price']['friday']),
							'saturday' => absint( $update['dow_price']['saturday']),
						)
				);

				// If prices is specifed only for sunday, copy Sunday price to all days
				if ($clean_updates[ $idx ]['dow_price']['monday'] == 0 &&
				    $clean_updates[ $idx ]['dow_price']['tuesday'] == 0 &&
				    $clean_updates[ $idx ]['dow_price']['wednesday'] == 0 &&
				    $clean_updates[ $idx ]['dow_price']['thursday'] == 0 &&
				    $clean_updates[ $idx ]['dow_price']['friday'] == 0 &&
				    $clean_updates[ $idx ]['dow_price']['saturday'] == 0 ) {

					$clean_updates[ $idx ]['dow_price']['monday'] =
						$clean_updates[ $idx ]['dow_price']['tuesday'] =
						$clean_updates[ $idx ]['dow_price']['wednesday'] =
						$clean_updates[ $idx ]['dow_price']['thursday'] =
						$clean_updates[ $idx ]['dow_price']['friday'] =
						$clean_updates[ $idx ]['dow_price']['saturday'] = $clean_updates[ $idx ]['dow_price']['sunday'];

				}
				$idx++;

			}

			update_post_meta( $post_id, 'meta_pricing_model_list', $clean_updates );
		}


	}

	/**
	 * Function get_pricing_model_list
	 *
	 * Retrieve the pricing model list option data.
	 *
	 * @access private
	 *
	 * @param $post_ID
	 *
	 * @return array|mixed
	 */
	private function get_pricing_model_list ( $post_ID ) {

		$list = get_post_meta($post_ID, 'meta_pricing_model_list', true);

		if ( $list == false || !isset( $list) || empty($list)  ) {
			return $this->get_pricing_model_template();
		}
		else {

			// update data structure if necessary
			// added version 1.0.3
			if ( !isset($list[0]['dow_price']) ) {
				$list[0]['dow_price'] = array(
					'sunday' => $list[0]['meta_room_price'],
					'monday' => $list[0]['meta_room_price'],
					'tuesday' => $list[0]['meta_room_price'],
					'wednesday' => $list[0]['meta_room_price'],
					'thursday' => $list[0]['meta_room_price'],
					'friday' => $list[0]['meta_room_price'],
					'saturday' => $list[0]['meta_room_price']
				);
			}
			return $list;
		}

	}

	/**
	 * Function get_pricing_model_template
	 *
	 * This function is called by get_pricing_model_list when there are no pricing models for
	 * a rooms post. It returns the empty structure of a pricing_model_list.
	 *
	 * @access private
	 *
	 * @param none
	 *
	 * @return array|mixed
	 */

	private function get_pricing_model_template () {

		$template = array();
		$template[] = array(
			'title'                    => '' ,
			'meta_room_pricing_date01' =>
				array(
					'date_start' => '' ,
					'date_end'   => ''
				),
			'meta_room_pricing_date02' =>
				array(
					'date_start' => '',
					'date_end'   => ''
				),
			'meta_room_pricing_date03' =>
				array(
					'date_start' => '',
					'date_end'   => ''
				),
			'meta_room_price'          => '',
			'dow_price' => array(
				'sunday' => '',
				'monday' => '',
				'tuesday' => '',
				'wednesday' => '',
				'thursday' => '',
				'friday' => '',
				'saturday' => ''
			)
		);

		return $template;
	}

}