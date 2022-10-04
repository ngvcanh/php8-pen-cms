<?php

function prr($vars, $name = ''){
    if ($GLOBALS['ENVIRONMENT'] !== 'production'){
        if ($name !== ''){
            echo "<p>{$name}--></p>";
        }
        echo '<pre>';
        print_r($vars);
        echo '</pre>';
    }
}

function url(){
    $args = func_get_args();
    return implode('/', array_merge([ $GLOBALS['BASE_URL'] ], $args));
}

function pathname(){
    $args = func_get_args();
    return implode(DIRECTORY_SEPARATOR, $args);
}

function classToPath($className){
    return str_replace(
        '/',
        DIRECTORY_SEPARATOR,
        str_replace('\\', DIRECTORY_SEPARATOR, $className)
    );
}

function path(String $pattern, mixed $handler){
    if (is_string($handler) || is_a($handler, '\\Core\\Classes\\Router')){
        return new Core\Classes\PathHandler($pattern, $handler);
    }
    
    return null;
}

function render(Core\Classes\Request $request, String $templete_file, $context = []){
    $template = new Core\Classes\Template($request, $templete_file, $context);
    return $template->render();
}

function include_app($path){
    global $BASE_DIR;
    return new Core\Classes\Router(pathname($BASE_DIR, 'apps', dotToPath($path)));
}

function dotToPath($name){
    return str_replace('.', DIRECTORY_SEPARATOR, $name);
}

function dotToNamespace($name){
    return str_replace('.', '\\', $name);
}

function escapePath($path){
    return str_replace(
        '\\',
        '\\\\',
        str_replace('/', '\\/', $path)
    );
}

function main(){
    Core\Classes\Main::load();
}