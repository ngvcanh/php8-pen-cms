<?php

namespace Core\Classes;

class Main{

    private $router;

    private $installedApp = [];

    private function __construct(){
        $this->request = new Request();
        $this->_loadInstalledApp();
        $this->_initRouter();
    }

    private function _loadInstalledApp(){
        global $INSTALLED_APPS, $BASE_DIR;
        
        $installedApp = isset($INSTALLED_APPS) ? $INSTALLED_APPS : [];
        if (!is_array($installedApp)) return false;

        foreach($installedApp as $app){
            $arr = explode('.', $app);
            $className = array_pop($arr);
            $appPath = pathname($BASE_DIR, 'apps', dotToPath(join('.', $arr))) . '.php';

            if (!file_exists($appPath)) continue;
            require($appPath);

            $className = '\\' . $className;
            array_push($this->installedApp, new $className());
        }
    }

    private function _initRouter(){
        global $BASE_DIR;
        $this->router = new Router(pathname($BASE_DIR, 'urls'));
    }

    static function load(){
        session_start();
        $GLOBALS['main_application'] = new self();
        $GLOBALS['main_application']->router->run();
    }

}