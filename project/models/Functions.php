<?php
namespace models;

class Functions
{
    public static function dump($content, $exit = false)
    {
        echo '<pre>' . print_r($content, 1) . '</pre>';
        if ($exit) {
            exit;
        }
    }
}