<?php

namespace App;

use Aura\SqlQuery\QueryFactory;
use PDO;

class SqlQuery{

  private $query;
  private $pdo;

  public function __construct(PDO $pdo, QueryFactory $query){
    $this->query = $query;
    $this->pdo = $pdo;
  }

  public function select($table){
    $select = $this->query->newSelect();

    $select->from("$table")
            ->cols([ "*" ]);
    $sth = $this->pdo->prepare($select->getStatement());
    $sth->execute($select->getBindValues());

    return $sth->fetchAll(PDO::FETCH_ASSOC);
  }

  public function selectJoin($table1, $table2){
    $sql = "SELECT * FROM users INNER JOIN userinfo ON users.id = userinfo.user_id ";

    $select = $this->pdo->prepare($sql);
    $select->execute();

    return $select->fetchAll(PDO::FETCH_ASSOC);
  }

  public function insert($data, $table){
    $insert = $this->query->newInsert();

    $insert
      ->into("$table")
      ->cols($data);

    $sth = $this->pdo->prepare($insert->getStatement());
    return $sth->execute($insert->getBindValues());
  }

}