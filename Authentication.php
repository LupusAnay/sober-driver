<?php
/**
 * Created by PhpStorm.
 * User: lupusanay
 * Date: 19.02.2018
 * Time: 13:51
 */

class Authentication
{
    /** @doc
     * @temp Производит проверку тела POST запроса (позже проверка будет делом отдельного модуля)
     * и создает нового пользователя
     * @temp на данный момент будет записывать просто в БД, без создания дополнительных маршрутов
     */
    public static function register(Base $f3, $params) {
        $body = json_decode($f3->get('BODY'));
        $data_invalid = false;
        //TODO Вынести валидацию входных данных для регистрации в отдельный модуль (mb класс)
        foreach($body as $key => $value) {
            if($key == "first_name" or $key == "second_name") {
                if(!preg_match('A-Za-zА-Яа-я', $value)) {
                    $data_invalid = true;
                    break;
                }
                if(strlen($value) <= 16 and strlen($value) > 1) {
                    $value = ucwords($value);
                } else {
                    $data_invalid = true;
                    break;
                }
            }
        }
    }

    public static function login() {
        //TODO Сделать это
    }

}