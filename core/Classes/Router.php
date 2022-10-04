<?php

namespace Core\Classes;

class Router{

    private $urlpatterns = [];

    private $root;

    function __construct(String $root){
        $this->root = $root;
        $this->_loadUrlPatterns();
    }

    private function _loadUrlPatterns(){
        global $BASE_DIR;
        
        $globalUrl = $this->root . '.php';
        require($globalUrl);

        if (!isset($urlpatterns)){
            throw new \Error('"$urlpatterns" is not found!');
        }

        if (!is_array($urlpatterns)){
            return false;
        }

        foreach($urlpatterns as $app){
            if (is_a($app, '\\Core\\Classes\\PathHandler')){
                $this->urlpatterns[$app->getPath()] = $app;
            }
        }
    }

    function run($prefix = ''){
        global $BASE_DIR, $main_application;

        $loaded = false;
        $pathname = trim(preg_replace(
            '/^' . escapePath($prefix) . '/', 
            '',
            trim($main_application->request->pathname, '/')
        ), '/');

        $app = preg_replace('/^' . escapePath($BASE_DIR) . '/', '', $this->root);
        $app = preg_replace('/urls$/', '', trim($app, DIRECTORY_SEPARATOR));
        $GLOBALS['running_app'] = trim(preg_replace('/^apps/', '', $app), DIRECTORY_SEPARATOR);

        foreach($this->urlpatterns as $url => $handler){
            $_url = trim($url, '/');
            $_path = trim($pathname, '/');

            if ($handler->isExact()){
                if ($handler->isFn()){
                    if ($_url !== $_path) continue;

                    $loaded = true;
                    $handler->callHandle($_url);
                }
                else{
                    $length = strlen($_url);
                    $subPath = substr($_path, 0, $length);

                    $nextChar = $length < strlen($_path) ? $_path[$length] : '';
                    if ($_url !== $subPath || !in_array($nextChar, ['', '/'])) continue;
                        
                    $loaded = true;
                    $handler->runHandle($_url);
                }
            }
            else{
                $regex = $this->parseToRegex(trim($url, '/'));
                if (!preg_match($regex, trim($pathname, '/'))) continue;

                if ($handler->isFn()){
                    $loaded = true;
                    $handler->callHandle($_url);
                }
                else{
                    $length = strlen($_url);
                    $subPath = substr($_path, 0, $length);

                    $nextChar = $length < strlen($_path) ? $_path[$length] : '';
                    if ($_url !== $subPath || !in_array($nextChar, ['', '/'])) continue;

                    $loaded = true;
                    $handler->runHandle($_url);
                }
            }

            if ($loaded) break;
        }

        if (!$loaded){
            $response = new HttpResponse('URL not found!', 404);
            $response->show();
        }
    }

    private function parseToRegex(String $path){
        $path = preg_replace('/\//', '\\/', $path);
        $path = preg_replace('/\<str\:[\w_]+\>/', '[\\w]+', $path);
        $path = preg_replace('/\<int\:[\w_]+\>/', '\\d+', $path);
        $path = preg_replace('/\<[\w_]+\>/', '[\\w]+', $path);
        return "/^{$path}/";
    }

}