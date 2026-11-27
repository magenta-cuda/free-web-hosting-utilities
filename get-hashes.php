<?php
$hashes = [];
$level = 0;
$found = [];
$is_http_request = array_key_exists('REQUEST_URI', $_SERVER);

$search = ($is_http_request && isset($_GET['search'])) ? $_GET['search']
            : ((!$is_http_request && ($v = array_find($argv, function(string $v, $k) {return substr_compare($v, 'search=', 0, 6) === 0;})))
                ? substr($v, 6) : false);

$limit = ($is_http_request && isset($_GET['level'])) ? intval($_GET['level'])
            : ((!$is_http_request && ($v = array_find($argv, function(string $v, $k) {return substr_compare($v, 'level=', 0, 6) === 0;})))
                ? intval(substr($v, 6)) : 8);

function do_files(string $dir) : void {
    global $level, $limit;
    if (!is_dir($dir)) {
        do_file($dir);
        return;
    }
    if (++$level > $limit) {
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
    global $search, $hashes, $found;
    if ($search) {
        if (strlen($file) > 4 && substr_compare($file, '.php', -4) === 0) {
            if (strpos(file_get_contents($file), $search) !== false) {
                $found[] = $file;
            }
        }
    } else {
        $hashes[$file] = hash_file('xxh3', $file);
    }
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
print_r($found);
print_r($hashes);
?>
</pre>
</body>
</html>
