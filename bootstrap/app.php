<?php
    
    //zona horaria
    date_default_timezone_set('America/Mexico_City');

    //cargar Helpers
    require_once __DIR__ .'/../core/handler.php';
    env();


    const REGWEBSITE = 'XgRsw78zQ';

    const ROL_ACCESS = 321;
    

    // Configuración de errores
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__. '/../storage/logs/error.log'); // Especifica la ruta al archivo de log

    // Cargar archivos de configuración de /config
    $configPath = __DIR__ .'/../config';
    foreach(glob($configPath .'/*.php') as $configFile){
        $configName = basename($configFile, '.php');
        $GLOBALS['config'][$configName] = require $configFile;
    }

    //cargar funciones utils
    foreach (glob(__DIR__ . '/../core/Helpers/*.php') as $utilFile) {
        require_once $utilFile;
    }