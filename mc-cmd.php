<?php
define('SHORTINIT', true);
require_once(__DIR__ . '/../wp-load.php');
if (array_key_exists('html', $_GET)) {
    echo '<html><body><pre>';
}
echo "mc-cmd loaded\n\n";
################################################################################
# print_r(explode(',', ini_get('disable_functions')));
print_r(get_option('active_plugins'));
################################################################################
if (array_key_exists('html', $_GET)) {
    echo '</pre></body></html>';
}

