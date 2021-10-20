<?php

namespace App\Controllers;


use App\Controllers\EmailController;
use Tamtamchik\SimpleFlash\Flash;

use Delight\Auth\Auth;
use PDO;

class UserController
{
  private $auth;
  private $pdo;

  public function __construct(PDO $pdo, Auth $auth){
    if( !session_id() ) @session_start();

    $this->pdo = $pdo;
    $this->auth = $auth;
    $this->flash = new Flash();
  }

  public function sign_up(){
    try {
      $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {

        header("Location: /pageLogin");
        return new EmailController($_POST['email'], $_POST['password'], $selector, $token);
      });

      echo 'We have signed up a new user with the ID ' . $userId;
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
      die('Invalid email address');
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
      die('Invalid password');
    }
    catch (\Delight\Auth\UserAlreadyExistsException $e) {
      die('User already exists');
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
      die('Too many requests');
    }

  }

  public function verefication(){
    try {
      $this->auth->confirmEmail($_GET['selector'], $_GET['token']);

      echo 'Email address has been verified';
    }
    catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
      die('Invalid token');
    }
    catch (\Delight\Auth\TokenExpiredException $e) {
      die('Token expired');
    }
    catch (\Delight\Auth\UserAlreadyExistsException $e) {
      die('Email address already exists');
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
      die('Too many requests');
    }

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