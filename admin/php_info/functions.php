<?php
$functions = get_defined_functions();
sort($functions['internal']);
sort($functions['user']);
//print_r($functions);
foreach ($functions['internal'] as $int) {
	echo $int . "<br />";
}
foreach ($functions['user'] as $int) {
	echo $int . "<br />";
}
?>