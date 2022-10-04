<?php

spl_autoload_register(function($className){

    if (preg_match('/^Core\\\\/', $className)){
        $className = preg_replace('/^Core/', 'core', $className);
        $classPath = pathname($GLOBALS['BASE_DIR'], classToPath($className)) . '.php';

        if (file_exists($classPath)){
            include_once $classPath;
        }
    }
    else{
        $pluginsDir = $GLOBALS['PLUGINS_DIR'];

        foreach($pluginsDir as $dir){
            $pluginPath = pathname($dir, classToPath($className)) . '.php';
            
            if (file_exists($pluginPath)){
                include_once $pluginPath;
                break;
            }
        } 
    }
    
});