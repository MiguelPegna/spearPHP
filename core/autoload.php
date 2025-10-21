<?php

    //load the classes
    $baseDir = dirname(__DIR__) . '/';
    spl_autoload_register(function($class) use ($baseDir){
        $path = $baseDir. str_replace('\\', '/', $class). '.php';  //TODO -> '/nomDir/className.php';
        file_exists($path) ? require_once($path) : die($path." - Class $class not found");
    });