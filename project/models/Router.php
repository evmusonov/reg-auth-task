<?php
namespace models;

use controllers\Controller;

class Router
{
    public static function parse()
    {
        $controller = new Controller();
        $uri = explode("?", $_SERVER['REQUEST_URI'])[0]; // Разбираем строку запроса

        if ($uri === '/') {
            return $controller->index();
        } else {
            $uri = mb_substr($uri, 1); // Убираем первый слеш для удобства
            $splitted = explode("/", $uri);
            if (!count($splitted) == 1) { // Если запрос типа site.ru/action одобряем, иначе выкидываем исключение
                throw new \Exception('Страница не найдена');
            }
            $action = $splitted[0];

            if (mb_strpos($action, '-') !== false) {
                $action = explode("-", $action);
                for($i = 1; $i < count($action); $i++) {
                    $action[$i] = ucfirst($action[$i]);
                }
                $action = implode("", $action);
            }

            if (!method_exists($controller, $action)) {
                throw new \Exception('Метод не найден');
            }

            return $controller->$action();
        }
    }
}