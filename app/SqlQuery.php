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

  public function select($id){
    $sql = <<<DOC
    SELECT * FROM users 
        INNER JOIN userinfo 
            ON users.id = userinfo.user_id 
                WHERE users.id = $id 
DOC;

    $select = $this->pdo->prepare($sql);
    $select->execute();

    return $select->fetchAll(PDO::FETCH_ASSOC);
  }

  public function selectJoin(){
    $sql = <<<DOC
    SELECT * FROM users 
        INNER JOIN userinfo 
            ON users.id = userinfo.user_id
DOC;

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

  public function updateUsers($data, $table, $id){

    $update = $this->query->newUpdate();

    $update->table($table)
    ->cols($data)
    ->where("id = $id");

    $sth = $this->pdo->prepare($update->getStatement());
    return $sth->execute($update->getBindValues());
  }

  public function updateUserinfo($data, $table, $id){

    $update = $this->query->newUpdate();

    $update->table($table)
      ->cols($data)
      ->where("user_id = $id");

    $sth = $this->pdo->prepare($update->getStatement());
    return $sth->execute($update->getBindValues());
  }

  public function delete($id){

    $sql = <<<HEAR
      DELETE FROM `users` WHERE `id` = $id; 
      DELETE FROM `userinfo` WHERE `user_id` = $id;
HEAR;

    $select = $this->pdo->prepare($sql);
    $select->execute();
  }

}