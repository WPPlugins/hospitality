<?php

use Omnipay\Omnipay;
/**
 * Created by PhpStorm.
 * User: weskempferjr
 * Date: 6/3/16
 * Time: 2:24 PM
 */
class PayPal_Express_Payment_Gateway implements Payment_Gateway
{
    
    private $credentials ;
    private $payment_info ;
    private static $omnipay_class = 'PayPal_Express' ;
    private $gateway ;
    private $currency ;

    /**
     * PayPal_Express_Payment_Gateway constructor.
     */
    public function __construct()
    {
        $this->credentials = array();
    }


    public function initialize_credentials( $options )
    {
        $this->options_to_credentials( $options );
        $this->currency = $options['hsp_payment_gateway_currency'];

        $this->gateway = Omnipay::create( self::$omnipay_class );
        $this->gateway->setUsername( $this->credentials['username'] );
        $this->gateway->setPassword( $this->credentials['password'] );
        $this->gateway->setSignature( $this->credentials['signature'] );
        $this->gateway->setTestMode( $this->credentials['test_mode'] );
       
    }

    public function initialize_payment( $reservation_request )
    {
        $this->payment_info = $this->reservation_to_payment_info( $reservation_request ) ;
        $this->payment_info['cancel_url'] = $this->credentials['cancel_url'];
        $this->payment_info['return_url'] = $this->credentials['return_url'];

    }

    public function process_payment()
    {
        $response = $this->gateway->purchase( $this->payment_info )->send();


        if ($response->isSuccessful()) {
            $payment_response = array(
                'status' =>  Payment_Gateway::PROCESS_STATUS_SUCCESS,
                'response' => $response,
                'data' => $response->getData(),
                'token' => ''
            );
        } elseif ($response->isRedirect()) {
            // redirect to offsite payment gateway
            // $response->redirect();
            $response_data = $response->getData();
            $payment_response = array(
                'status' => Payment_Gateway::PROCESS_STATUS_REDIRECT,
                'url' => $response->getRedirectUrl(),
                'data' => $response_data,
                'token' => $response_data['TOKEN']
            );

        } else {
            $payment_response = array(
                'status' => Payment_Gateway::PROCESS_STATUS_FAIL,
                'message' => $response->getMessage(),
                'data' => $response->getData(),
                'token' => ''
            );

        }

        return $payment_response;
    }

    /*
     * 
     */
    public function confirm_payment()
    {
        $response = $this->gateway->fetchCheckout()->send();

        if ($response->isSuccessful()) {

            $response_data = $response->getData();
            $token = $response_data['TOKEN'];
            $reservation = Reservations_Post_Type::get_reservation_by_token( $token );

            if ( $reservation != false ) {
                // It matches. Complete the transaction.
                $response = $this->gateway->completeAuthorize(  array( 'amount' => floatval( $reservation['payment_amount'] ) ) )->send();

                if ($response->isSuccessful()) {
                    $confirmation_response = array(
                        'status' => Payment_Gateway::PROCESS_STATUS_SUCCESS,
                        'amount' => $reservation['payment_amount'],
                        'reservation_id' => $reservation['id'],
                        'response' => $response
                    );
                }
                else {
                    $confirmation_response = array(
                        'status' => Payment_Gateway::PROCESS_STATUS_CONFIRMATION_FAILED,
                        'response' => $response
                    );

                }

            }


        } else {
            $confirmation_response = array(
                'status' => Payment_Gateway::PROCESS_STATUS_VERIFICATION_FAILED,
                'message' => $response->getMessage(),
                'data' => $response->getRedirectData()
            );

        }



        return $confirmation_response;

    }
    
    private function reservation_to_payment_info( $reservation_request ) {
        //   
        $payment_info = array(
            'description' => $reservation_request['title'],
            'amount' => floatval($reservation_request['payment_amount']),
            'currency' => $this->currency
        );
        
        return $payment_info;
    }

    private function options_to_credentials( $options) {


        $this->credentials['username'] = $options['hsp_payment_gateway_username'];
        $this->credentials['password'] = $options['hsp_payment_gateway_password'];
        $this->credentials['signature'] = $options['hsp_payment_gateway_signature'];
        $this->credentials['test_mode'] = $options['hsp_payment_gateway_test_mode'];
        $this->credentials['cancel_url'] = $options['hsp_payment_gateway_cancel_url'];
        $this->credentials['return_url'] = $options['hsp_payment_gateway_return_url'];
    }
}