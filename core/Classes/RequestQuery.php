<?php

namespace Core\Classes;

class RequestQuery{

    private $name;

    function __construct($name){
        $this->name = strtoupper($name);
    }

    function get(){
        $name = '_' . $this->name;
        if (!isset($GLOBALS[$name])) return null;

        $query = $GLOBALS[$name];
        $args = func_get_args();

        if (!isset($args[0])) return $query;
        return isset($query[$args[0]]) ? $query[$args[0]] : null;
    }

    function serialize(){
        $name = '_' . $this->name;
        return !empty($GLOBALS[$name]) ? $GLOBALS[$name] : new \stdClass;
    }

}