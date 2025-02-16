<?php
session_start();

define('BASEPATH', dirname(__DIR__));
define('APPPATH', BASEPATH . '/app');
define('COREPATH', APPPATH . '/core');
define('MODELPATH', APPPATH . '/models');
define('CONTROLLERPATH', APPPATH . '/controllers');
define('CONFIGPATH', APPPATH . '/config');
define('VIEWPATH', APPPATH . '/view');
spl_autoload_register(function ($class) {
    $paths = [
        COREPATH,
        MODELPATH,
        CONTROLLERPATH,
        CONFIGPATH
    ];

    foreach ($paths as $path) {
        $file = $path . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    error_log("Autoload failed for class: " . $class);
});

require_once CONFIGPATH . '/config.php';
