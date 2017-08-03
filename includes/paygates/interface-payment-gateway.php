<?php

/**
 *
 */
if (!defined('GUESTABA_HSP_ON'))
    define('GUESTABA_HSP_ON', __('On', GUESTABA_HSP_TEXTDOMAIN));

if (!defined('GUESTABA_HSP_OFF'))
    define('GUESTABA_HSP_OFF', __('Off', GUESTABA_HSP_TEXTDOMAIN));

interface Payment_Gateway
{

    
    // return values for process payment. 
    const PROCESS_STATUS_SUCCESS = 'success';
    const PROCESS_STATUS_FAIL = 'fail';
    const PROCESS_STATUS_REDIRECT = 'redirect';
    const PROCESS_STATUS_OFFLINE = 'offline_payment';
    const PROCESS_STATUS_VERIFICATION_FAILED = 'verification failed';
    const PROCESS_STATUS_CONFIRMATION_FAILED = 'confirmation failed';

    

    public function initialize_credentials( $credentials );
    public function initialize_payment( $payment_info );
    public function process_payment();
    public function confirm_payment();
}