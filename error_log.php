<?php
if (!array_key_exists('raw', $_GET)) {
?>
<html>
<body>
<pre>
<?php
}
$ini_err_log = ini_get('error_log');
echo "ini_get('error_log') = ";
print_r($ini_err_log);
echo "\n";
if (array_key_exists('set', $_GET)) {
    $err_log = __DIR__ . '/php-err.log';
    ini_set('error_log', $err_log);
    $ini_err_log = ini_get('error_log');
    echo "ini_get('error_log') = ";
    print_r($ini_err_log);
    echo "\n";
}
echo "file_exists('{$ini_err_log}') = ";
$err_log_exists = file_exists($ini_err_log);
print_r($err_log_exists);
echo "\n"; 
if ($err_log_exists) {
    if (array_key_exists('clr', $_GET)) {
        file_put_contents($ini_err_log, '');
    }
    if (array_key_exists('tst', $_GET)) {
        error_log('Hello');
    }
    echo "file_get_contents('{$ini_err_log}') = \n";
    $contents = file_get_contents($ini_err_log);
    if (strlen($contents) > 16384) {
        $contents = '...' . substr($contents, -16384);
    }
    echo $contents;
}
if (!array_key_exists('raw', $_GET)) {
?>
</pre>
</body>
</html>
<?php
}
