<?php

header('Content-Type: application/javascript');

$js_file = str_replace('\\', '/', realpath(GTFW_BASE_DIR . 'main/js'));

define('GTFW_JS',$js_file . '/*.js');

foreach (glob(GTFW_JS) as $value) {
	echo file_get_contents($value);
}

?>