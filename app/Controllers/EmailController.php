<?php

namespace App\Controllers;

use SimpleMail;

class EmailController
{
  public function __construct($email, $username, $selector, $token){
    SimpleMail::make()
      ->setTo($email, $username)
      ->setFrom("GlebGrigoriev@mail.ru", "Gleb")
      ->setSubject("Selector and Token")
      ->setMessage("Селектор: $selector </br> Токен: $token")
      ->send();
  }
}