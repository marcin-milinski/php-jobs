<?php

ini_set('display_errors', 'on');
error_reporting(E_ALL & ~E_DEPRECATED);

// that's enough if using namespaces, it will find the correct class assuming namespaces
// map the project folder structure
spl_autoload_extensions('.php');
spl_autoload_register();
