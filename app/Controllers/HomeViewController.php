<?php

namespace App\Controllers;

use League\Plates\Engine;
use \Delight\Auth\Auth;
use PDO;

class HomeViewController
{

  private $engine;
  private $auth;
  private $pdo;

  public function __construct(PDO $pdo, Engine $engine, Auth $auth){
    if( !session_id() ) @session_start();

    $this->pdo = $pdo;
    $this->engine = $auth;
    $this->engine = $engine;
  }

  public function users()
  {
    echo $this->engine->render('users');
  }

  public function pageLogin(){
    echo $this->engine->render('page_login');
  }

  public function pageRegistr(){
    echo $this->engine->render('page_register');
  }

  public function pageVerefication(){
    echo $this->engine->render('page_verefication');
  }

}