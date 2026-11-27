<?php
$hashes = [];
$level = 0;
$is_http_request = array_key_exists('REQUEST_URI', $_SERVER);

function do_files(string $dir) : void {
    global $argv, $is_http_request, $level;
    if (!is_dir($dir)) {
        do_file($dir);
        return;
    }
    ++$level;
    if (($is_http_request && isset($_GET['level']) && $level > intval($_GET['level']))
            || (!$is_http_request && ($v = array_find($argv, function(string $v, $k) {return substr_compare($v, 'level=', 0, 6) === 0;}))
                && $level > intval(substr($v, 6)))) {
        return;
    }
    foreach (scandir($dir) as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        do_files($dir . '/' . $file);
    }
    --$level;
}

function do_file(string $file) : void {
    global $hashes;
    $hashes[$file] = hash_file('xxh3', $file);
}

do_files(__DIR__ . '/' . ($is_http_request ? $_GET['dir'] : $argv[1]));
ksort($hashes);

if (($is_http_request && isset($_GET['serialize'])) || (!$is_http_request && in_array('serialize', $argv))) {
	echo serialize($hashes);
	die();
}
if (($is_http_request && isset($_GET['raw'])) || (!$is_http_request && in_array('raw', $argv))) {
	print_r($hashes);
	die();
}
?>
<html>
<body>
<pre>
<?php
print_r($hashes);
?>
</pre>
</body>
</html>
