<?php
/*
$dir = __DIR__;
$uri = $_SERVER['REQUEST_URI'];
$path = $dir . substr($uri, 0, strpos($uri, '?'));
?>
<html><body><pre>
<?php
echo "\ndir = ";
print_r($dir);
echo "\nuri = ";
print_r($uri);
echo "\npath = ";
print_r($path);
echo "\n";
?>
</pre></body></html>
<?php
die();
 */
error_log(__DIR__ . substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'] . '?', '?')));
if (file_exists(__DIR__ . substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'] . '?', '?')))) {
    return false;
}
require_once __DIR__ . '/index.php';
