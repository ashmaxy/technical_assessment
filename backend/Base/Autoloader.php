<?php

function autoload($class) {
    
    $file = BASE_PATH . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file))
        require_once $file;
    else {
        throw new Exception(sprintf('Class { %s } Not Found!', $class));
    }
}

spl_autoload_register('autoload');
