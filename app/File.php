<?php

namespace App;

class File
{
  public function __construct($data)
  {
    $this->dir = "img/demo/avatars/";

    $this->name = $data['name'];
    $this->tmp = $data['tmp_name'];
  }

  public function upLoad(){
    $upload = $this->dir . basename($this->name);
    move_uploaded_file($this->tmp, $upload);
  }

  public function pathFile(){
    return $this->dir . basename($this->name);
  }
}