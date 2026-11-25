<?php
$host = '';
$dir = '/wp-content/';
$ftp_host = '';
$user = '';
$pass = '';
$ftp_dir = '/wp-content/';
$user_agent = 'Mozilla/5.0 (X11; Linux x86_64; rv:140.0) Gecko/20100101 Firefox/140.0';

include './wp-eval-conf.php';
if ($argc >= 4) {
    $host = $argv[1];
    $user = $argv[2];
    $pass = $argv[3];
    if ($argc >= 5) {
        for ($i = 5; $i < $argc; $i++) {
            if (($j = strpos($argv[$i], '=')) !== false) {
                $var = substr($argv[$i], 0, $j);
                $val = substr($argv[$i], $j + 1);
                $$var = $val;
            }
        }
    }
}
while (true) {
  	system('geany mc-cmd.php');
	  $cmd = './phpstan.phar analyze --level=9 mc-cmd.php';
	  echo "\$ {$cmd}\n";
	  system($cmd, $ret);
	  if (!$ret) {
		    break;
	  }
	  echo "Ignore errors and continue? ";
	  if (is_string($ans = fgets(STDIN)) && strtolower(substr(trim($ans), 0, 1))  === 'y') {
		    break;
	  }
}
if ($ftp_host === '') {
    if (substr_compare($host, 'https://', 0) === 0) {
        $ftp_host = substr($host, 8);
    } else if (substr_compare($host, 'http://', 0) === 0) {
        $ftp_host = substr($host, 7);
    }
}
if (!($conn = ftp_connect($ftp_host))) {
    die("ftp_connect('{$ftp_host}') failed.\n");
}
if (!ftp_login($conn, $user, $pass)) {
    die("ftp_login() failed.\n");
}
ftp_pasv($conn, true);
echo 'ftp_pwd(): ' . ftp_pwd($conn) . "\n";
print_r(ftp_nlist($conn, '/'));
print_r(ftp_nlist($conn, '/htdocs'));
$remote = $ftp_dir . 'mc-cmd.php';
echo "ftp_delete(, '{$remote}') = ";
print_r(ftp_delete($conn, $remote));
echo "\n";
print_r(ftp_nlist($conn, $ftp_dir));
$path = __DIR__ . '/mc-cmd.php';
echo "ftp: \"{$path}\" -> \"{$remote}\"\n";
if (ftp_put($conn, $remote, $path, FTP_ASCII) === false) {
    die("ftp_put(, {$remote}, {$path},) failed.");
}
print_r(ftp_nlist($conn, $ftp_dir));
ftp_close($conn);
if ($host === 'http://m-cuda.dx.am') {
}
$url = "{$host}{$dir}mc-cmd.php";
$curl_ret = false;
if (function_exists('curl_init')) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    if (($buffer = curl_exec($curl)) !== false) {
        if (strpos($buffer, 'mc-cmd loaded') !== false) {
            echo $buffer;
            die();
        }
    }
} else {
    echo "curl_init() does not exists, using passthru('curl ...') instead.\n";
}
if (file_exists($file = __DIR__ . '/curl.cmd')) {
    $cmd = file_get_contents($file);
} else {
    $accept = 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';    /* */
    $accept_language = 'en-US,en;q=0.5';
    $accept_encoding = 'gzip, deflate, br, zstd';
    $cmd = "curl -L -b /dev/null {$url} --compressed -H 'User-Agent: {$user_agent}' -H 'Accept: {$accept}' -H 'Accept-Language: {$accept_language}'"
               . " -H 'Accept-Encoding: {$accept_encoding}' -H 'Upgrade-Insecure-Requests: 1'";
}
echo "{$cmd}\n";
ob_start();
passthru($cmd, $curl_ret);
$buffer = ob_get_clean();
if ($curl_ret === 0 && strpos($buffer, 'mc-cmd loaded') !== false) {
    echo $buffer;
    die();
}
echo "curl('{$url}') failed, redirecting to firefox.\n";
system("firefox {$url}?html=1");
