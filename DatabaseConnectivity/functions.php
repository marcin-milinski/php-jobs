<?php

// When you create a subfolder structure matching the namespaces of the containing classes, 
// you will never even have to define an autoloader like one commented below.
spl_autoload_extensions(".php");
spl_autoload_register();
  
/*
spl_autoload_register(function ($class) {
    include $class . '.php';
});
*/

// putting message together, usually for Exceptions
function __($string, array $values = null)
{
    return empty($values) ? $string : strtr($string, $values);
}