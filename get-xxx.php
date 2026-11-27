<?php
if (!array_key_exists('raw', $_GET)) {
?>
<html>
<body>
<pre>
<?php
}
echo "get_defined_functions(true) =\n";
print_r($defined_functions = get_defined_functions(true));
echo "\n";
echo "get_declared_classes() =\n";
print_r($declared_classes = get_declared_classes());
echo "\n";
if (in_array('ini_get_all', $defined_functions['internal'])) {
    echo "ini_get_all() =\n";
    print_r(ini_get_all());
    echo "\n";
}
if (!array_key_exists('raw', $_GET)) {
?>
</pre>
</body>
</html>
<?php
}
