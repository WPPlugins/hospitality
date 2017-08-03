<?php

/**
 * Created by PhpStorm.
 * User: weskempferjr
 * Date: 6/3/16
 * Time: 4:51 PM
 */
class Payment_Gateway_Factory
{

    const PAYPAL_EXPRESS = 'paypal_express';
    const STRIPE = 'stripe';
    const OFFLINE = 'offline';

    private static $gateway_names = array(
        'none' => '',
        self::PAYPAL_EXPRESS => 'PayPal Express',
        self::OFFLINE => 'Offline'
    );
    
    private static $test_mode_options = array(
        'true' =>  GUESTABA_HSP_ON,
        'false' => GUESTABA_HSP_OFF
    );

    private static  $enable_offline_options = array(
        'true' =>  GUESTABA_HSP_ON,
        'false' => GUESTABA_HSP_OFF
    );

    public static function create_payment_gateway( $gateway_name ) {

        $options = get_option( GUESTABA_HSP_OPTIONS_NAME) ;

        switch ( $gateway_name ) {

            case self::PAYPAL_EXPRESS:
                $gateway = new PayPal_Express_Payment_Gateway();
                $gateway->initialize_credentials( $options );
                break;

            case self::OFFLINE:
                $gateway = new Offline_Payment_Gateway();
                break;

            default:
                error_log( __FILE__ . ':' . __LINE__ . ','.  __('Unknown payment gateway', GUESTABA_HSP_TEXTDOMAIN));
                throw new Exception( __('Unknown payment gateway', GUESTABA_HSP_TEXTDOMAIN));
                break;

        }

        return $gateway;
        
    }
    
    public static function detect_confirmation_payment_method() {
        
        $qvars = array_keys( $_GET );
        if ( $qvars[0] == 'token' && $qvars[1] == 'PayerID'){
            $payment_method = self::PAYPAL_EXPRESS ;
        }
        else {
            error_log( __FILE__ . ':' . __LINE__ . ','.  __('Cannot determine payment method from confirmation query.', GUESTABA_HSP_TEXTDOMAIN));
            throw new Exception( __('Cannot determine payment method from confirmation query.', GUESTABA_HSP_TEXTDOMAIN));
        }
        
        return $payment_method;
    }

    public static function get_gateway_names() {
        return self::$gateway_names;
    }

    public static function get_test_mode_options() {
        return self::$test_mode_options;
    }
    
    public static function get_enable_offline_options() {
        return self::$enable_offline_options;
    }


}