<?php

use App\SqlQuery;

if( !session_id() ) @session_start();
require "../vendor/autoload.php";

$builder = new DI\ContainerBuilder();
$builder->addDefinitions('../app/Config/ContainerDefinition.php');

$container = $builder->build();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {

//  View
  $r->addRoute('GET', '/', ['App\Controllers\HomeViewController', 'index']);
  $r->addRoute('GET', '/users[?{id:\d+}]', ['App\Controllers\HomeViewController', 'users']);
  $r->addRoute('GET', '/pageLogin', ['App\Controllers\HomeViewController', 'pageLogin']);
  $r->addRoute('GET', '/pageRegistr', ['App\Controllers\HomeViewController', 'pageRegistr']);
  $r->addRoute('GET', '/pageVerefication[?{id:\d+}]', ['App\Controllers\HomeViewController', 'pageVerefication']);
  $r->addRoute('GET', '/pageCreate[?{id:\d+}]', ['App\Controllers\HomeViewController', 'pageCreate']);

//  Functions
  $r->addRoute('POST', '/log_in', ['App\Controllers\UserController', 'log_in']);
  $r->addRoute('POST', '/sign_up', ['App\Controllers\UserController', 'sign_up']);
  $r->addRoute('POST', '/verefication[?{id:\d+}]', ['App\Controllers\UserController', 'verefication']);
  $r->addRoute('POST', '/create[?{id:\d+}]', ['App\Controllers\UserController', 'create']);
  $r->addRoute('GET', '/logout', ['App\Controllers\UserController', 'logout']);

});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
  $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
  case FastRoute\Dispatcher::NOT_FOUND:

    break;
  case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
    $allowedMethods = $routeInfo[1];

    break;
  case FastRoute\Dispatcher::FOUND:
    $handler = $routeInfo[1];
    $vars = $routeInfo[2];

    $container->call($routeInfo[1], $routeInfo[2]);
    break;
}