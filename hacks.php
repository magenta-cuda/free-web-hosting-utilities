<?php
/**
 * Plugin Name: Hacks
 * Version: 0.1
 * Author: Nemo
 */

add_filter('got_url_rewrite', function($got_url_rewrite) { return true; });
