<?php

use League\Plates\Engine;

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
  }
];