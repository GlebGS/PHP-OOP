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

        flash()->success("<b>Уведомление!</b> Регистрация прошла успешно. Мы отправили сообщение на ваш Email!");
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
      $this->auth->confirmEmail($_POST['selector'], $_POST['token']);

      flash()->success("<b>Уведомление!</b> Email подтверждён!");
      header("Location: /pageLogin");die;
    }
    catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
      flash()->success("<b>Уведомление!</b> Email подтверждён!");
      header("Location: /pageVerefication");die;
    }
  }

  public function log_in(){
    try {
      $this->auth->login($_POST['email'], $_POST['password']);
      $id = $this->auth->getUserId();

      header("Location: /users?id=$id");die;
    }
    catch (\Delight\Auth\InvalidEmailException $e) {
      flash()->error("Неправильный адрес электронной почты!");
      header("Location: /pageLogin");die;
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
      flash()->error("Неправильный пароль!");
      header("Location: /pageLogin");die;
    }
    catch (\Delight\Auth\EmailNotVerifiedException $e) {
      flash()->error("Электронная почта не подтверждена!");
      header("Location: /pageLogin");die;
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
      flash()->error("Слишком много запросов!");
      header("Location: /pageLogin");die;
    }
  }



}