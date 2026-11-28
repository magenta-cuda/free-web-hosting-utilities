<?php
require_once(__DIR__ . '/../wp-load.php');
if (!current_user_can('upload_files')) {
    die('You are not allowed to upload files. You must be logged in as an administrator to run this tool.');
}
?>
<html><body><pre>
<?php
echo "upload request =\n";
print_r($_FILES);
echo "\n";
$uploaded = __DIR__ . '/uploaded.php';
rename($_FILES['mc-cmd_php']['tmp_name'], $uploaded);
$buffer = file_get_contents($uploaded);
$from = strpos($buffer, "####\n") + 5;
$to = strpos($buffer, "\n####", $from) + 1;
echo "The PHP code is:\n#############################################################################\n\n";
echo substr($buffer, $from, $to - $from);
echo "\nThe result is:\n#############################################################################\n";
include $uploaded;
echo "\n#############################################################################\n\n";
?>
</pre></body></html>
