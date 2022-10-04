<?php

namespace Core\Classes;

use \Twig\Loader\FilesystemLoader as FilesystemLoader;
use \Twig\Environment as Environment;

class Template{

    private $context = [];

    private $file;

    private $request;

    function __construct($request, $template_file, $context){
        $this->request = $request;
        $this->file = $template_file;
        $this->context = $context;
    }

    function render(){
        global $BASE_DIR, $TEMPLATES_DIR, $running_app;
        $templateDir = $TEMPLATES_DIR;
        
        if ($running_app !== ''){
            array_push($templateDir, pathname($BASE_DIR, 'apps', $running_app, 'templates'));
        }

        $this->context['request'] = $this->request->serialize();

        $loader = new FilesystemLoader($templateDir);
        $twig = new Environment($loader);
        $template = $twig->load($this->file);

        return $template->render($this->context);
    }

    private function getTemplatePath($filename){
        global $TEMPLATES_DIR, $BASE_DIR;
        
        $path = pathname($BASE_DIR, $filename);
        if (file_exists($path)) return $path;

        $existed= false;

        if (is_array($TEMPLATES_DIR)){
            foreach($TEMPLATES_DIR as $folder){
                $path = pathname($folder, $filename);
                if (file_exists($path)){
                    $existed = true;
                    break;
                }
            }
        }

        if (!$existed){
            throw new \Error('Template "' . $filename . '" is not found');
        }

        return $path;
    }

}