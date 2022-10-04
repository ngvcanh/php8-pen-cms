<?php

namespace Core\Classes;

class HttpResponse{

    static $CODE = [
        '200' => 'Successful',
        '404' => 'Not Found'
    ];

    private $content = '';

    function __construct(String $content = '', $status = 200, String $contentType = 'text/html'){
        $this->content = $content;
        $this->setHeaderStatus($status)
            ->setHeaderContentType($contentType);
    }

    private function setHeaderStatus($code){
        header('HTTP/1.0 ' . $code . ' ' . HttpResponse::$CODE[$code]);
        return $this;
    }

    private function setHeaderContentType($type){
        header('Content-Type: ' . $type . ';charset=UTF-8');
        return $this;
    }

    function render(){
        return $this->content;
    }

    function show(){
        echo $this->content;
    }

}