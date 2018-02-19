<?php
/**
 * Created by PhpStorm.
 * User: lupusanay
 * Date: 19.02.2018
 * Time: 13:51
 */

namespace Controller;

$f3 = require('../lib/base.php');

class Auth
{
    public static function register($params) {

        header('Content-Type: application/json');
        echo json_encode("Hello from registration");
    }

    public static function login() {
        //TODO Сделать это
    }

}