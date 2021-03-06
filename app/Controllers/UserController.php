<?php

namespace App\Controllers;

use App\Controllers\EmailController;
use Tamtamchik\SimpleFlash\Flash;
use Aura\SqlQuery\QueryFactory;
use App\File;
use App\SqlQuery;

use Delight\Auth\Auth;
use PDO;

class UserController
{

  private $query;
  private $auth;
  private $pdo;

  public function __construct(PDO $pdo, Auth $auth, QueryFactory $query)
  {
    if (!session_id()) @session_start();

    $this->pdo = $pdo;
    $this->auth = $auth;
    $this->query = $query;

    $this->sqlQuery = new SqlQuery($this->pdo, $this->query);
  }

  public function sign_up()
  {
    try {
      $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {

        flash()->success("<b>Уведомление!</b> Регистрация прошла успешно. Мы отправили сообщение на ваш Email!");
        return new EmailController($_POST['email'], $_POST['password'], $selector, $token);
      });

      header("Location: /pageVerefication?id=$userId");
      die;
    } catch (\Delight\Auth\InvalidEmailException $e) {
      flash()->error("Неверный адрес электронной почты!");
      header("Location: /pageRegistr");
      die;
    } catch (\Delight\Auth\InvalidPasswordException $e) {
      flash()->error("Неверный пароль!");
      header("Location: /pageRegistr");
      die;
    } catch (\Delight\Auth\UserAlreadyExistsException $e) {
      flash()->error("Пользователь уже существует!");
      header("Location: /pageRegistr");
      die;
    } catch (\Delight\Auth\TooManyRequestsException $e) {
      flash()->error("Слишком много запросов!");
      header("Location: /pageRegistr");
      die;
    }

  }

  public function verefication()
  {
    try {
      $this->auth->confirmEmail($_POST['selector'], $_POST['token']);
      $id = $_GET['id'];

      flash()->success("<b>Уведомление!</b> Email подтверждён!");
      header("Location: /pageLogin");
      return $this->sqlQuery->insert(['user_id' => $id, 'img' => ' ', 'position' => ' ', 'phone' => ' ', 'address' => ' '], 'userinfo');
    } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
      flash()->success("<b>Уведомление!</b> Email подтверждён!");
      header("Location: /pageVerefication");
      die;
    }
  }

  public function log_in()
  {
    try {
      $this->auth->login($_POST['email'], $_POST['password']);
      $id = $this->auth->getUserId();

      header("Location: /users?id=$id");
      die;
    } catch (\Delight\Auth\InvalidEmailException $e) {
      flash()->error("Неправильный адрес электронной почты!");
      header("Location: /pageLogin");
      die;
    } catch (\Delight\Auth\InvalidPasswordException $e) {
      flash()->error("Неправильный пароль!");
      header("Location: /pageLogin");
      die;
    } catch (\Delight\Auth\EmailNotVerifiedException $e) {
      flash()->error("Электронная почта не подтверждена!");
      header("Location: /pageLogin");
      die;
    } catch (\Delight\Auth\TooManyRequestsException $e) {
      flash()->error("Слишком много запросов!");
      header("Location: /pageLogin");
      die;
    }
  }

  public function logout()
  {
    header("Location: /users");
    die;
  }

  public function addRoll($userId)
  {
    try {
      $this->auth->admin()->addRoleForUserById($userId, \Delight\Auth\Role::ADMIN);
    } catch (\Delight\Auth\UnknownIdException $e) {
      die('Unknown user ID');
    }
  }

  public function create()
  {
    try {
      $file = new File($_FILES['file']);
      $userId = $this->auth->admin()->createUser($_POST['email'], $_POST['password'], $_POST['username']);

      $data = [
        'user_id' => $userId,
        'img' => "/" . $file->pathFile(),
        'position' => $_POST['position'],
        'phone' => $_POST['phone'],
        'address' => $_POST['address'],
        'status_user' => $_POST['status']
      ];

      $file->upLoad();
      $this->sqlQuery->insert($data, 'userinfo');

      $id = $this->auth->getUserId();

      flash()->success("<b>Уведомлени!</b> Пользователь успешно добавлен!");
      header("Location: /users?id=$id");
      die;
    } catch (\Delight\Auth\InvalidEmailException $e) {
      flash()->error("<b>Уведомлени!</b> Invalid email address!");
      header("Location: /pageCreate");
      die;
    } catch (\Delight\Auth\InvalidPasswordException $e) {
      flash()->error("<b>Уведомлени!</b> Invalid password!");
      header("Location: /pageCreate");
      die;
    } catch (\Delight\Auth\UserAlreadyExistsException $e) {
      flash()->error("<b>Уведомлени!</b> User already exists!");
      header("Location: /pageCreate");
      die;
    }
  }

  public function update(){
    $id = $_GET['id'];

    $this->sqlQuery->updateUsers([
      'username' => $_POST['username']
    ], "users", $id);

    $this->sqlQuery->updateUserinfo([
      'position' => $_POST['position'],
      'phone' => $_POST['phone'],
      'address' => $_POST['address']
    ], "userinfo", $id);
  }

  public function security_func(){
    try {
      $id = $_GET['id'];
      $this->auth->changePassword($_POST['password'], $_POST['password_reset']);

      flash()->success("<b>Уведомление!</b> Пароль был успешно изменен!");
      header("Location: /users?id=$id");
      die;
    }
    catch (\Delight\Auth\NotLoggedInException $e) {
      flash()->error("<b>Уведомление!</b> Не вошел!");
      header("Location: /security?id=$id");
      die;
    }
    catch (\Delight\Auth\InvalidPasswordException $e) {
      flash()->error("<b>Уведомление!</b> Неверный пароль!");
      header("Location: /security?id=$id");
      die;
    }
    catch (\Delight\Auth\TooManyRequestsException $e) {
      flash()->error("<b>Уведомление!</b> Слишком много запросов!");
      header("Location: /security?id=$id");
      die;
    }

  }

  public function status_func(){
    $id = $_GET['id'];

    $this->sqlQuery->updateUserinfo([
      'status_user' => $_POST['option']
    ], "userinfo", $id);

    header("Location: /users?id=$id");
    die;
  }

  public function media_func(){
    $id = $_GET['id'];
    $file = new File($_FILES['file']);

    $data = [ 'img' => "/" . $file->pathFile() ];

    $this->sqlQuery->updateUserinfo( $data,'userinfo', $id);
    $file->upLoad();

    flash()->success("<b>Уведомлени!</b> Изображение было успешно измененно!");
    header("Location: /users?id=$id");
    die;
  }

  public function delete_func(){
    $id = $_GET['id'];
    $this->sqlQuery->delete($id);

    header("Location: /users?id=$id");
    die;
  }

}