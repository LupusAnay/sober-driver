<?php
/**
 * Created by PhpStorm.
 * User: 05041
 * Date: 20.02.2018
 * Time: 19:18
 */

//TODO Добвать документацию
class Validator
{
    public function validateValue($value) {
        $msg = "Неверная цена\r\n";
        if (strlen($value) > 4) {
            return $msg;
        }
        return "";
     }
    public function validateCoordinates($value) {
        $msg = "Неверные координаты\r\n";
        if (!preg_match('/^([-+]?)([\d]{1,2})(((\.)(\d+)(,)))(\s*)(([-+]?)([\d]{1,3})((\.)(\d+))?)$/', $value)) {
            return $msg;
        }
        return "";
    }

    public function validateName($name)
    {
        $msg = "Неверное имя\n";
        if (!preg_match('/^[A-zА-я]+$/u', $name)) {
            return $msg;
        }
        if (strlen($name) > 32 or strlen($name) <= 2) {
            return $msg;
        }
        return "";
    }

    public function validateDate($date)
    {
        $msg = "Неверная дата\r\n";
        if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
            return $msg;
        }

        $d1 = new DateTime($date);
        $d2 = new DateTime(date('y-m-d'));
        $diff = $d2->diff($d1);

        if ($diff->y < 18) {
            return $msg;
        }
        return "";
    }

    public function validateDoc($value)
    {
        $msg = "Неверный номер документа\r\n";
        if (!preg_match('/^[0-9]{10}$/', $value)) {
            return $msg;
        }
        return "";
    }

    public function validatePhone($value)
    {
        $msg = "Неверный телефон\r\n";
        if (!preg_match('/^((\+7)+([0-9]){10})$/', $value)) {
            return $msg;
        }
        return "";
    }
}