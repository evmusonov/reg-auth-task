<?php
namespace controllers;

use models\DB;
use models\Functions;
use models\Render;

class Controller
{
    public function index()
    {
        if (isset($_SESSION['user'])) {
            header("Location: /profile");
            exit;
        }

        return Render::view('index');
    }

    public function register()
    {
        if (isset($_POST['fio'], $_POST['login'], $_POST['email'], $_POST['password'])) {
            $errors = [];

            if (empty($_POST['fio'])) {
                $errors[] = 'Заполните ФИО';
            }
            if (empty($_POST['login'])) {
                $errors[] = 'Заполните логин';
            }
            if (empty($_POST['email'])) {
                $errors[] = 'Заполните e-mail';
            }
            if (empty($_POST['password'])) {
                $errors[] = 'Заполните пароль';
            }

            // Проверяем наличие пользователя с такой почтой
            $db = DB::getDB();
            $query = "SELECT * FROM `users` WHERE `email` = '{$db->escapeString($_POST['email'])}'";
            $rows = $db->select($query);
            if (count($rows)) {
                $errors[] = 'Данный e-mail уже зарегистрирован';
            }

            if (count($errors)) {
                return Render::view('index', ['regErrors' => $errors]);
            } else {
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $query = "INSERT INTO `users` (fio, login, email, password) 
                    VALUES ('" . htmlspecialchars($_POST['fio']) ."', '" . htmlspecialchars($_POST['login']) ."', '" . htmlspecialchars($_POST['email']) ."', '" . $password ."')";

                $id = $db->query($query);
                if (is_int($id)) { // $db->query() возвращает id записи, проверяем прошла ли вставка успешно
                    $_SESSION['notification'] = 'Вы успешно зарегистрированы';
                    header("Location: /");
                    exit;
                }
            }
        }

        header("Location: /");
        exit;
    }

    public function login()
    {
        if (isset($_POST['email'], $_POST['password'])) {
            $errors = [];

            if (empty($_POST['email'])) {
                $errors[] = 'Заполните e-mail';
            }
            if (empty($_POST['password'])) {
                $errors[] = 'Заполните пароль';
            }

            $db = DB::getDB();
            $query = "SELECT * FROM `users` WHERE `email` = '{$db->escapeString($_POST['email'])}'";
            $user = $db->selectRow($query);
            if ($user === false) {
                $errors[] = 'Данного пользователя не существует';
            } else {
                $passwordVerify = password_verify($_POST['password'], $user['password']);

                if ($passwordVerify) {
                    $_SESSION['user'] = $user;
                    header("Location: /profile");
                    exit;
                } else {
                    $errors[] = 'Неверный пароль';
                }
            }

            if (count($errors)) {
                return Render::view('index', ['loginErrors' => $errors]);
            }
        }

        header("Location: /");
        exit;
    }

    public function profile()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /");
            exit;
        }

        return Render::view('profile');
    }

    public function changeData()
    {
        if (isset($_POST['fio'])) {
            $errors = [];

            if (empty($_POST['fio'])) {
                $errors[] = 'Заполните ФИО';
            }

            $db = DB::getDB();

            if (count($errors)) {
                return Render::view('profile', ['dataErrors' => $errors]);
            } else {
                $fio = htmlspecialchars($_POST['fio']);
                $query = "UPDATE `users` SET `fio` = '$fio' WHERE `id` = {$db->escapeString($_SESSION['user']['id'])}";
                if ($db->query($query)) {
                    $_SESSION['user']['fio'] = $fio;
                    $_SESSION['notification'] = 'ФИО изменены';
                } else {
                    $_SESSION['notification'] = 'Произошла ошибка';
                }

                header("Location: /profile");
                exit;
            }
        }

        header("Location: /");
        exit;
    }

    public function changePassword()
    {
        if (isset($_POST['old_password'], $_POST['new_password'], $_POST['repeat_password'])) {
            $errors = [];

            if (empty($_POST['old_password'])) {
                $errors[] = 'Введите старый пароль';
            } else {
                $verify = password_verify($_POST['old_password'], $_SESSION['user']['password']);
                if (!$verify) {
                    $errors[] = 'Неверный старый пароль';
                }
            }
            if (empty($_POST['new_password'])) {
                $errors[] = 'Введите новый пароль';
            }
            if (empty($_POST['repeat_password'])) {
                $errors[] = 'Повторите пароль';
            }
            if ($_POST['new_password'] !== $_POST['repeat_password']) {
                $errors[] = 'Проверьте правильность повторного ввода пароля';
            }

            $db = DB::getDB();

            if (count($errors)) {
                return Render::view('profile', ['passwordErrors' => $errors]);
            } else {
                $newPassword = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
                $query = "UPDATE `users` SET `password` = '$newPassword' WHERE `id` = {$db->escapeString($_SESSION['user']['id'])}";
                if ($db->query($query)) {
                    $_SESSION['user']['password'] = $newPassword;
                    $_SESSION['notification'] = 'Пароль изменен';
                } else {
                    $_SESSION['notification'] = 'Произошла ошибка';
                }

                header("Location: /profile");
                exit;
            }
        }

        header("Location: /");
        exit;
    }

    public function logout()
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
            header("Location: /");
            exit;
        }

        header("Location: /");
        exit;
    }
}