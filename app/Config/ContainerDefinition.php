<?php

use Aura\SqlQuery\QueryFactory;
use League\Plates\Engine;
use \Delight\Auth\Auth;

return [
  PDO::class => function(){
    $driver = 'mysql';
    $host = 'localhost';
    $db = 'marlin';
    $charset = 'utf8';
    $login = 'root';
    $password = '';

    return new PDO("$driver:host=$host;dbname=$db;charset=$charset", "$login", "$password");
  },

  \League\Plates\Engine::class => function(){
    return new \League\Plates\Engine('../app/Views/');
  },

  \Delight\Auth\Auth::class => function($container){
    return new \Delight\Auth\Auth($container->get('PDO'));
  },

  \Aura\SqlQuery\QueryFactory::class => function(){
    return new QueryFactory('mysql');
  }

];