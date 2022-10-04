<?php

namespace Core\Classes;

class PathHandler{

    private $path;

    private $handler;

    private $params = [];

    function __construct(String $path, mixed $handler){
        $this->path = $path;

        if (is_string($handler) || is_a($handler, '\\Core\\Classes\\Router')){
            $this->handler = $handler;
        }

        $this->_parsePath();
    }

    function getPath(){
        return $this->path;
    }

    function isExact(){
        return empty($this->params);
    }

    function isFn(){
        return is_string($this->handler);
    }

    function callHandle($prefix){
        global $main_application;

        if (is_callable($this->handler)){
            $name = $this->handler;
            $params = [];
            
            if (!empty($this->params)){
                $path = trim($main_application->request->pathname, '/');
                $regex = $this->_parsePathToRegex(trim($this->path, '/'));
                preg_match_all($regex, $path, $matches);
                $params = $matches[1];
            }

            $result = call_user_func_array($name, array_merge([ $main_application->request ], $params));
            
            $response = new HttpResponse($result);
            $response->show();
        }
    }

    function runHandle($prefix){
        $this->handler->run($prefix);
    }

    private function _parsePath(){
        preg_match_all('/(\<((str|int):)?[\w_]+\>)/', $this->path, $matches);
        
        $params = $matches[0];
        if (empty($params)) return false;
        
        foreach($params as $text){
            $text = preg_replace('/<|>/', '', $text);
            $arr = explode(':', $text);
            
            if (isset($arr[1])){
                array_push($this->params, [
                    'type' => $arr[0],
                    'name' => $arr[1]
                ]);
            }
            else{
                array_push($this->params, [
                    'type' => 'str',
                    'name' => $arr[0]
                ]);
            }
        }
    }

    private function _parsePathToRegex(String $path){
        $path = preg_replace('/\//', '\\/', $path);
        $path = preg_replace('/\<str:[\w_]+\>/', '(\w+)', $path);
        $path = preg_replace('/\<int:[\w_]+\>/', '(\d+)', $path);
        $path = preg_replace('/\<[\w_]+\>/', '(\w+)', $path);
        return "/{$path}/";
    }

}