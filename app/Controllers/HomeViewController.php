<?php

namespace App\Controllers;

use League\Plates\Engine;
use PDO;

class HomeViewController
{

  private $engine;
  private $pdo;

  public function __construct(PDO $pdo, Engine $engine)
  {
    $this->engine = $engine;
    $this->pdo = $pdo;
  }

  public function users()
  {
    echo $this->engine->render('users');
  }

}