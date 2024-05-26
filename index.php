<?php
use Pero\Controllers;

require_once realpath("vendor/autoload.php");
require_once realpath("config.php");

$uri = $_SERVER['REQUEST_URI'];
$uri = strtok($uri, '?');
$uri = str_replace("pero","",$uri);
$uri = str_replace("index.php","",$uri);
$uri = trim($uri,"/");

$routeSchema = !empty($uri) ? explode("/",$uri) : [];

$controllerName = $routeSchema[0] ?? DefaultController;
$method = $routeSchema[1] ?? 'index';

$controllerClassName = '\\Pero\\Controllers\\' . ucfirst($controllerName);

if(class_exists($controllerClassName)) {
    $controller = new $controllerClassName();
    $controller->$method();
} else {
    echo "404"; 
}