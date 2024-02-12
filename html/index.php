<?php
require '../vendor/autoload.php';
require '../base/config.php';
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL | E_NOTICE);

$route = new \Base\Route();
$route->add('/', \App\Controller\LoginController::class);
$route->add('/login/register', \App\Controller\LoginController::class, 'register');
$route->add('/login/auth', \App\Controller\LoginController::class, 'auth');
$route->add('/blog', \App\Controller\BlogController::class);
$route->add('/blog/addMessage', \App\Controller\BlogController::class, 'addMessage');
$route->add('/api/message', \App\Controller\ApiController::class, 'getUserMessages');
$app = new \Base\Application($route);
$app->run();
