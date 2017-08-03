<?php
/**
 * The template for displaying Room Archive pages.
 * 
 * This file be overriden and customized by copying it to a theme directory. 
 *
 *
 * @package Hospitality
 * @since 1.0.0
 *
 *
 */

$site_url = get_site_url();

header('HTTP/1.1 301 Moved Permanently');
header('Location: ' . $site_url  . '/' . GUESTABA_ROOMS_LISTING_PAGE_NAME );
die;

?>
