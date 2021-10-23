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

  public function insert($id, $table, $data){
    $insert = $this->query->newInsert();

    $insert->into($table)
          ->cols($data)
          ->bindValues($data);

    $sth = $this->pdo->prepare($insert->getStatement());
    $sth->execute($insert->getBindValues());
  }

}