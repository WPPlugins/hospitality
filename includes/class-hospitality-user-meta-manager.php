<?php

/**
 * Created by PhpStorm.
 * User: weskempferjr
 * Date: 7/14/16
 * Time: 5:07 PM
 */
class Hospitality_User_Meta_Manager
{

    /**
     * Hospitality_User_Meta_Manager constructor.
     */
    public function __construct()
    {
    }

    /*
     *
     * TODO: acknowlegement http://bavotasan.com/2009/adding-extra-fields-to-the-wordpress-user-profile/
     *
     *
     */
    public function render_extra_profile_fields ( $user ) {
        ?>

        <table class="form-table">
            <tr>
                <th><label for="address1"><?php _e("Address 1"); ?></label></th>
                <td>
                    <input type="text" name="address1" id="address1" value="<?php echo esc_attr( get_the_author_meta( 'address1', $user->ID ) ); ?>" class="regular-text" /><br />
                </td>
            </tr>
            <tr>
                <th><label for="address2"><?php _e("Address 2"); ?></label></th>
                <td>
                    <input type="text" name="address2" id="address2" value="<?php echo esc_attr( get_the_author_meta( 'address2', $user->ID ) ); ?>" class="regular-text" /><br />
                    <span class="description"><?php _e("Please enter your address."); ?></span>
                </td>
            </tr>
            <tr>
                <th><label for="city"><?php _e("City"); ?></label></th>
                <td>
                    <input type="text" name="city" id="city" value="<?php echo esc_attr( get_the_author_meta( 'city', $user->ID ) ); ?>" class="regular-text" /><br />
                </td>
            </tr>
            <tr>
                <th><label for="state"><?php _e("State"); ?></label></th>
                <td>
                    <input type="text" name="state" id="state" value="<?php echo esc_attr( get_the_author_meta( 'state', $user->ID ) ); ?>" class="regular-text" /><br />
                </td>
            </tr>
            <tr>
                <th><label for="postalcode"><?php _e("Postal Code"); ?></label></th>
                <td>
                    <input type="text" name="postal_code" id="postal_code" value="<?php echo esc_attr( get_the_author_meta( 'postal_code', $user->ID ) ); ?>" class="regular-text" /><br />
                </td>
            </tr>
            <tr>
                <th><label for="country"><?php _e("Country"); ?></label></th>
                <td>
                    <input type="text" name="country" id="country" value="<?php echo esc_attr( get_the_author_meta( 'country', $user->ID ) ); ?>" class="regular-text" /><br />
                </td>
            </tr>
            <tr>

            <tr>
                <th><label for="phone"><?php _e("Phone Number"); ?></label></th>
                <td>
                    <input type="text" name="phone" id="phone" value="<?php echo esc_attr( get_the_author_meta( 'phone', $user->ID ) ); ?>" class="regular-text" /><br />
                </td>
            </tr>
            <tr>
                <th><label for="mobile-phone"><?php _e("Mobile Phone Number"); ?></label></th>
                <td>
                    <input type="text" name="mobile" id="mobile" value="<?php echo esc_attr( get_the_author_meta( 'mobile', $user->ID ) ); ?>" class="regular-text" /><br />
                </td>
            </tr>
        </table>

        <?php

    }

    public function save_extra_profile_fields( $user_id ) {

        if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }

        update_user_meta( $user_id, 'address1', sanitize_text_field( $_POST['address1'] ));
        update_user_meta( $user_id, 'address2', sanitize_text_field( $_POST['address2'] ));
        update_user_meta( $user_id, 'city',sanitize_text_field( $_POST['city'] ) );
        update_user_meta( $user_id, 'state', sanitize_text_field($_POST['state'] ));
        update_user_meta( $user_id, 'postal_code', sanitize_text_field($_POST['postal_code'] ));
        update_user_meta( $user_id, 'phone', sanitize_text_field($_POST['phone'] ));
        update_user_meta( $user_id, 'mobile', sanitize_text_field($_POST['mobile'] ));
        update_user_meta( $user_id, 'country', sanitize_text_field($_POST['country'] ));


    }
    
    public static function register_user( $registration_request ) {

        // Email will be used as user name.
        $user_id  = register_new_user( $registration_request['email'] , $registration_request['email']);
        if ( !is_wp_error($user_id ) ) {
            update_user_meta( $user_id, 'first_name', $registration_request['first_name'] );
            update_user_meta( $user_id, 'last_name', $registration_request['last_name'] );
            update_user_meta( $user_id, 'address1', $registration_request['address1'] );
            update_user_meta( $user_id, 'address2', $registration_request['address2']);
            update_user_meta( $user_id, 'city',$registration_request['city']);
            update_user_meta( $user_id, 'state', $registration_request['state']);
            update_user_meta( $user_id, 'postal_code', $registration_request['postal_code']);
            update_user_meta( $user_id, 'phone', $registration_request['phone']);
            update_user_meta( $user_id, 'mobile', $registration_request['mobile']);
            update_user_meta( $user_id, 'country', $registration_request['country']);

        }
        else {
            $error_string = $user_id->get_error_message();
            throw new Exception( sprintf( __('Could not register user', GUESTABA_HSP_TEXTDOMAIN ) . ': %s', $error_string ));
        }

        $user = array(
            'id' => $user_id,
            'login' => $registration_request['email'],
            'email' => $registration_request['email'],
            'firstName' => $registration_request['first_name'],
            'lastName' => $registration_request['last_name'],
            'displayName' => $registration_request['email'],
            'address1' =>  $registration_request['address1'],
            'address2' =>  $registration_request['address2'],
            'city' =>  $registration_request['city'],
            'state' =>  $registration_request['state'],
            'postalCode' =>  $registration_request['postal_code'],
            'phone' =>  $registration_request['phone'],
            'mobile' =>  $registration_request['mobile'],
            'country' => $registration_request['country'],
        );
        return $user ;
        
    }

    public static function get_user() {

        $current_user = wp_get_current_user();
        
        if ( is_user_logged_in()) {

            $user = array(
                'id' => $current_user->ID,
                'login' => $current_user->user_login,
                'email' => $current_user->user_email,
                'firstName' => $current_user->user_firstname,
                'lastName' => $current_user->user_lastname,
                'displayName' => $current_user->display_name,
                'address1' =>  get_the_author_meta( 'address1', $current_user->ID ),
                'address2' =>  get_the_author_meta( 'address2', $current_user->ID ),
                'city' =>  get_the_author_meta( 'city', $current_user->ID ),
                'state' =>  get_the_author_meta( 'state', $current_user->ID ),
                'postalCode' =>  get_the_author_meta( 'postalcode', $current_user->ID ),
                'phone' =>  get_the_author_meta( 'phone', $current_user->ID ),
                'mobile' =>  get_the_author_meta( 'mobile_phone', $current_user->ID ),
                'country' => get_the_author_meta( 'country', $current_user->ID ),
            );
        } 
        else {
            $user = array (
                'id' => 0
            );
            
        }
        
        return $user;

    }
    
    

    public static function update_user () {

    }

}