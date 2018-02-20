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

    public function validateName($name)
    {
        if (!preg_match('[A-Za-zА-Яа-я]', $name)) {
            return false;
        }
        if (strlen($name) > 16 and strlen($name) <= 1) {
            return false;
        }
        return true;
    }

    public function validateDate($date)
    {
        if (!preg_match('[([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))]', $date)) {
            return false;
        }

        $d1 = new DateTime($date);
        $d2 = new DateTime(date('y-m-d'));
        $diff = $d2->diff($d1);

        if ($diff->y < 18) {
            return false;
        }
        return true;
    }

    public function validateDoc($value)
    {
        if (!preg_match('[0-9]{10}', $value)) {
            return false;
        }
        return true;
    }

    public function validatePhone($value)
    {
        if (!preg_match('^((\+7)+([0-9]){10})$', $value)) {
            return false;
        }
        return true;
    }
}