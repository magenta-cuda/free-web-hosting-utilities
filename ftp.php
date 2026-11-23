<?php
$host = $argv[1];
$user = $argv[2];
$pass = $argv[3];
if ($argc < 6) {
    die(1);
}
if ($argv[4] === 'raw') {
    $cmd = $argv[5];
} else {
    $local_dir = $argv[4];
    if ($local_dir === './') {
        $local_dir = getcwd() . '/';
    } else if ($local_dir[0] !== '/') {
        $local_dir = getcwd() . '/' . $local_dir;
    }
    $remote_dir = $argv[5];
}
if (isset($local_dir) && isset($remote_dir) && $argc === 7) {
    $content = file_get_contents("${local_dir}$argv[6]");
    if (!is_string($content)) {
        die(2);
    }
    $target = "ftp://{$user}:{$pass}@{$host}" . ($remote_dir[0] === '/' ? '' : '/') . "{$remote_dir}{$argv[6]}";
    echo "${target}\n";
    flush();
    if (file_put_contents($target, $content) !== false) {
        die();
    }
}
if (!($conn = ftp_connect($host))) {
    die(4);
}
if (!ftp_login($conn, $user, $pass)) {
    die(5);
}
ftp_pasv($conn, true);
if (isset($cmd)) {
    print_r(ftp_set_option($conn, FTP_USEPASVADDRESS, false));
    print_r(ftp_pasv($conn, true));
    print_r(ftp_raw($conn, 'STAT'));
    echo "${cmd}\n";
    if (strpos($cmd, 'NLST') === 0) {
        print_r(ftp_nlist($conn, substr($cmd, 5)));
    } else if (strpos($cmd, 'MLSD') === 0) {
        print_r(ftp_mlsd($conn, substr($cmd, 5)));
    } else if (strpos($cmd, 'PWD') === 0) {
        print_r(ftp_pwd($conn));
    } else if (strpos($cmd, 'LIST') === 0) {
        print_r(ftp_nlist($conn, substr($cmd, 5)));
    } else {
        print_r(ftp_raw($conn, $cmd));
    }
    ftp_close($conn);
    die();
}
if (isset($local_dir) && isset($remote_dir) && $argc >= 7) {
    for ($i = 6; $i < $argc; $i++) {
        echo "\n\"" . $local_dir . $argv[$i] . '" -> "' . $remote_dir . $argv[$i] . "\"\n";
        if (ftp_put($conn, $remote_dir . $argv[$i], $local_dir . $argv[$i], FTP_ASCII) === false) {
            die(6);
        }
    }
}
ftp_close($conn);
