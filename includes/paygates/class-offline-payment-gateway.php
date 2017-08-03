<?php

/**
 * Created by PhpStorm.
 * User: weskempferjr
 * Date: 6/8/16
 * Time: 9:11 AM
 */
class Offline_Payment_Gateway implements Payment_Gateway
{
    private $credentials ;
    
    public function __construct()
    {
        $this->credentials = array();
    }


    public function initialize_credentials($credentials)
    {
        // This function is a no-op for this gateway.
    }

    public function initialize_payment($payment_info)
    {
        // This function is a no-op for this gateway.
    }

    public function process_payment()
    {
        $response = array(
            'status' => Payment_Gateway::PROCESS_STATUS_SUCCESS
        );
        
        return $response;
    }

    public function confirm_payment()
    {
        // This function is a no-op for this gateway.
    }
}