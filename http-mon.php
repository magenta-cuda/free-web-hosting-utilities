<?php
/**
 * Plugin Name: HTTP-mon
 * Version: 0.1
 * Author: Nemo
 */

add_filter('pre_http_request', function($false, $parsed_args, $url) {
    if (in_array(parse_url($url, PHP_URL_HOST), ['api.wordpress.org', 'public-api.wordpress.com', 'woocommerce.com', 'pixel.wp.com'])) {
        error_log("http-mon.php has blocked HTTP request {$url}");
		    return new WP_Error('http_request_not_executed', sprintf(__('http-mon.php has blocked requests through HTTP to the URL: %s.'), $url));
    } else {
        error_log('pre_http_request:$url = ' . print_r($url, true));
        error_log(wp_debug_backtrace_summary());
    }
    return false;
}, 10, 3);

# temporary hack to force twentytwentyfive theme on woocommerce pages

add_filter('option_template',function($Lhd_){
if(preg_match('#/(my-account|shop|cart|checkout)(/|\?)#',$_SERVER['REQUEST_URI'])){
#error_log("option_template = {$Lhd_}");
#error_log(wp_debug_backtrace_summary());
return 'twentytwentyfive';}
return $Lhd_;},11);
add_filter('option_stylesheet',function($Lhd_){
if(preg_match('#/(my-account|shop|cart|checkout)(/|\?)#',$_SERVER['REQUEST_URI'])){
#error_log("option_stylesheet = {$Lhd_}");
#error_log(wp_debug_backtrace_summary());
return 'twentytwentyfive';}
return $Lhd_;},11);
