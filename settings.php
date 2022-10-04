<?php

$ENVIRONMENT = 'development';

$BASE_DIR = __DIR__;

$ROOT_URL = $_SERVER['DOCUMENT_ROOT'];

$BASE_URL = str_replace(
    str_replace('\\', '/', $ROOT_URL), 
    '', 
    str_replace('\\', '/', $BASE_DIR)
);

$PLUGINS_DIR = [
    $BASE_DIR . DIRECTORY_SEPARATOR . 'plugins'
    
];

$INSTALLED_APPS = [
    'cms.apps.CMSApp'
];

$DATABASES = [
    'default' => [
        'ENGINE' => 'core.Classes.Database.MySQLi'
    ]
];

$TEMPLATES_DIR = [
    $BASE_DIR . DIRECTORY_SEPARATOR . 'templates',
];