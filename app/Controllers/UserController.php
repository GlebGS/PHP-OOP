<?php

namespace App\Controllers;

use Delight\Auth\Auth;
use PDO;

class UserController
{
  private $auth;
  private $pdo;

  public function __construct(PDO $pdo, Auth $auth){
    $this->pdo = $pdo;
    $this->auth = $auth;
  }

  public function sign_up(){

    $data = [
      'username' => $_POST['username'],
      'email' => $_POST['email'],
      'password' => $_POST['password'],
    ];
    d($data);

//    try {
//      $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {
//        echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
//      });
//
//      echo 'We have signed up a new user with the ID ' . $userId;
//    }
//    catch (\Delight\Auth\InvalidEmailException $e) {
//      die('Invalid email address');
//    }
//    catch (\Delight\Auth\InvalidPasswordException $e) {
//      die('Invalid password');
//    }
//    catch (\Delight\Auth\UserAlreadyExistsException $e) {
//      die('User already exists');
//    }
//    catch (\Delight\Auth\TooManyRequestsException $e) {
//      die('Too many requests');
//    }

  }

  public function log_in(){
    try {
      $this->auth->login($_POST['email'], $_POST['password']);

      echo 'User is logged in';
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
      die('Wrong email address');
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
      die('Wrong password');
    }
    catch (\Delight\Auth\EmailNotVerifiedException $e) {
      die('Email not verified');
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
      die('Too many requests');
    }
  }

}