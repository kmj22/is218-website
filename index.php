<?php
  ini_set('display_errors', 'On');
	function my_autoloader($class) {
		include $class . '.class.php';
	}
	spl_autoload_register('my_autoloader');

	$app = new app;

?>