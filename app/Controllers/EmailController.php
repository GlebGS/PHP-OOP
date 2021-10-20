<?php

namespace App\Controllers;

use SimpleMail;

class EmailController
{
  public function __construct($email, $username, $selector, $token){
    SimpleMail::make()
      ->setTo($email, $username)
      ->setFrom("Gleb@mail.ru", "Gleb Grigoriev")
      ->setSubject("Selector and Token")
      ->setMessage("Селектор: $selector Токен: $token")
      ->send();
  }
}