<?php
/**
 * Created by PhpStorm.
 * User: Sariel Aki ~desu~
 * Date: 28.02.2018
 * Time: 19:46
 */

class Order
{
    public static function get(Base $f3)
    {
        $result = $f3->get('DB')->exec('SELECT * FROM orders WHERE id = ?',
            $f3->get('PARAMS.id'));
        if (count($result) != 0) {
            echo json_encode($result);
        } else {
            echo $f3->error('404');
        }
    }
    public static function put(Base $f3) {
        $f3->get('DB')->exec("UPDATE orders SET status = 'taken' WHERE id = ?",
        $f3->get('PARAMS.id'));
    }
    public static function findAll(Base $f3) {
        $result = $f3->get('DB')->exec('SELECT * FROM orders');
        echo json_encode($result);
    }
    public static function addNewOrder(Base $f3) {
        //TODO: Пильнуть добавление заказа
    }
}
