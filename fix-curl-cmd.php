#!/usr/bin/env php
<?php
$host = '.com';
$command = '/wp-content/mc-cmd.php';
if (isset($argv[2])) {
    $command = $argv[2];
}
$buffer = file_get_contents($argv[1]);
$buffer = preg_replace('#-H#', "\\\n-H", $buffer);
$buffer = preg_replace('#' . str_replace('.', '\.', $host) . '/[^\']+\'#', "{$host}{$command}' --compressed", $buffer);
echo $buffer;
