<?php
/**
 * Created by PhpStorm.
 * User: lupusanay
 * Date: 19.02.2018
 * Time: 13:51
 */

//TODO Добвать документацию
class Authentication
{
    /** @doc
     * @temp Производит проверку тела POST запроса (позже проверка будет делом отдельного модуля)
     * и создает нового пользователя
     * @temp на данный момент будет записывать просто в БД, без создания дополнительных маршрутов
     * @param Base $f3 - переменная Класса Дескриптора фреймворка FatFree
     * @param $params - параметры запроса
     */
    public static function register(Base $f3)
    {
        $body = json_decode($f3->get('BODY'), true);

        if (Authentication::validateRegistrationData($body)) {
            /**
             * @warn
             * Использование обычного HTTP для передачи паролей без шифрования не безопасно.
             */
            $body['password'] = password_hash($body['password'], PASSWORD_DEFAULT);

            $f3->get('DB')->exec(
                'INSERT INTO employees (first_name, second_name, birthday, passport, driver_license,  phone, password, points) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?, 0)',
                array_values($body)
            );

        } else {
            echo $f3->error('422', 'Неверные данные для регистрации');
        }
    }

    public static function login(Base $f3)
    {
        $body = json_decode($f3->get('BODY'), true);
        $db = $f3->get('DB');
        $result = $f3->get('DB')->exec('SELECT password FROM employees WHERE phone = :phone', array(':phone' => $body['phone']));
        return (bool)password_verify($body['password'], $result[0]['password']);
    }

    public static function validateRegistrationData($body)
    {
        $validator = new Validator();
        $keys = ['first_name', 'second_name', 'birthday', 'passport', 'driver_license', 'phone', 'password'];
        $isValid = true;

        if (count($body) == 7) {
            for ($i = 0; $i < count($body); $i++) {
                if (!array_key_exists($keys[$i], $body)) {
                    $isValid = false;
                    break;
                }
            }
            $result = 0;

            $result += !$validator->validateName($body['first_name']);
            $result += !$validator->validateName($body['second_name']);
            $result += !$validator->validateDate($body['birthday']);
            $result += !$validator->validateDoc($body['passport']);
            $result += !$validator->validateDoc($body['driver_license']);
            $result += !$validator->validatePhone($body['phone']);

            if ($result != 0) {
                $isValid = false;
            }
        } else {
            $isValid = false;
        }
        return $isValid;
    }
}