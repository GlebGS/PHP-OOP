<?php

namespace App\Controllers;

use App\SqlQuery;
use Aura\SqlQuery\QueryFactory;
use League\Plates\Engine;
use \Delight\Auth\Auth;
use PDO;

class HomeViewController
{
  private $engine;
  private $query;
  private $auth;
  private $pdo;

  public function __construct(PDO $pdo, Engine $engine, Auth $auth, QueryFactory $query){
    if( !session_id() ) @session_start();

    $this->pdo = $pdo;
    $this->auth = $auth;
    $this->query = $query;
    $this->engine = $engine;

    $this->id = $this->auth->getUserId();
    $this->sqlQuery = new SqlQuery($this->pdo, $this->query);
    $this->userControll = new UserController($this->pdo, $this->auth, $this->query);
  }

  public function index(){
    header("Location: /pageLogin");die;
  }

  public function users(){
    if ($_GET['id']):
      $posts = $this->sqlQuery->select("users");

      echo $this->engine->render('users', [
        'posts' => $posts,
        'userControll' => $this->userControll,
        'getRole' => $this->auth->getRoles()
      ]);
    else:
      header("Location: /pageLogin");die;
    endif;
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

  public function pageCreate(){
    echo $this->engine->render('create_user', [
      'id' => $this->id
    ]);
  }

}