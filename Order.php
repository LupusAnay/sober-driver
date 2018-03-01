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
        $result = $f3->get('DB')->exec('SELECT * FROM orders, executed_orders');
        echo json_encode($result);
    }
    public static function addNewOrder(Base $f3){
        $body = json_decode($f3->get('BODY'), true);

        if (Order::validateOrderData($body)) {
            $f3->get('DB')->exec(
                'INSERT INTO orders (`from`, `to`, value) VALUE (?, ?, ?)',
                array_values($body)
            );
        } else {
            echo $f3->error('422', 'Неверные данные для заказа');
        }
    }
    public static function validateOrderData($body) {
        $validator = new Validator();
        $keys = ['from', 'to', 'value'];
        $isValid = true;

        if (count($body) == 3) {
            for ($i = 0; $i < count($body); $i++) {
                if (!array_key_exists($keys[$i], $body)) {
                    $isValid = false;
                    break;
                }
            }
            $result = 0;

            $result += !$validator->validateValue($body['value']);
            $result += !$validator->validateCoordinates($body['from']);
            $result += !$validator->validateCoordinates($body['to']);

            if ($result != 0) {
                $isValid = false;
            }
        } else {
            $isValid = false;
        }
        return $isValid;
    }
}
