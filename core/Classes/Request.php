<?php

namespace Core\Classes;

class Request{

    static $QUERY = ['get', 'post', 'files', 'server', 'cookies', 'session'];

    static $PROPS = ['base_url', 'fullpath', 'pathname'];

    function __construct(){
        $this
            -> _initQuery()
            ->_setBaseUrl()
            ->_setQueryString()
            ->_setFullpath()
            ->_setPathname();
    }

    private function _initQuery(){
        foreach(Request::$QUERY as $name){
            $this->{strtoupper($name)} = new RequestQuery($name);
        }
        return $this;
    }

    private function _setBaseUrl(){
        global $BASE_URL;
        $this->base_url = $BASE_URL;
        return $this;
    }

    private function _setFullpath(){
        $uri = $this->SERVER->get('REQUEST_URI');
        $uri = is_null($uri) ? '' : $uri;

        $query = $this->query_string;
        $query = is_null($query) ? '' : $query;
        $this->fullpath = preg_replace('/\?' . $query . '/', '', $uri);

        return $this;
    }

    private function _setPathname(){
        $this->pathname = preg_replace(
            '/^' . str_replace('/', '\\/', $this->base_url) . '/',
            '',
            $this->fullpath
        );

        return $this;
    }

    private function _setQueryString(){
        $this->query_string = $this->SERVER->get('QUERY_STRING');
        return $this;
    }

    function serialize(){
        $arr = [
            'params' => !empty($this->params) ? $this->params : new \stdClass
        ];

        foreach(Request::$QUERY as $name){
            $name = strtoupper($name);
            if ($name === 'FILES') continue;
            $arr[$name] = $this->{$name}->serialize();
        }

        foreach(Request::$PROPS as $prop){
            $arr[$prop] = $this->{$prop};
        }
        
        return $arr;
    }

}