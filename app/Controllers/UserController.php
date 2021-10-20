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
  }

  public function sign_up(){
    try {
      $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {

        flash()->success("<b>Уведомлени!</b> Регистрация прошла успешно. Мы отправили сообщение на ваш Email!");
        header("Location: /pageVerefication");
        return new EmailController($_POST['email'], $_POST['password'], $selector, $token);
      });
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
      flash()->error("Неверный адрес электронной почты!");
      header("Location: /pageRegistr");die;
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
      flash()->error("Неверный пароль!");
      header("Location: /pageRegistr");die;
    }
    catch (\Delight\Auth\UserAlreadyExistsException $e) {
      flash()->error("Пользователь уже существует!");
      header("Location: /pageRegistr");die;
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
      flash()->error("Слишком много запросов!");
      header("Location: /pageRegistr");die;
    }

  }

  public function verefication(){
    try {
      $this->auth->confirmEmail($_GET['selector'], $_GET['token']);

      flash()->success("<b>Уведомлени!</b> Email подтверждён!");
      header("Location: /pageLogin");die;
    }
    catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
      flash()->success("<b>Уведомлени!</b> Email подтверждён!");
      header("Location: /pageLogin");die;
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