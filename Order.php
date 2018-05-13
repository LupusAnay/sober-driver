<?php
/**
 * Created by PhpStorm.
 * User: Sariel Aki ~desu~
 * Date: 28.02.2018
 * Time: 19:46
 */

class Order extends Main
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

    public static function put(Base $f3)
    {
        $f3->get('DB')->exec("UPDATE orders SET status = 'taken' WHERE id = ?",
            $f3->get('PARAMS.id'));
    }

    public static function findAll(Base $f3)
    {
        $result = $f3->get('DB')->exec('SELECT * FROM orders');
        echo json_encode($result);
    }

    public static function addNewOrder(Base $f3)
    {
        $body = json_decode($f3->get('BODY'), true);
        $body['value'] = (float) $body['value'];
        $msg = Order::validateOrderData($body);
        if ($msg === true) {
            array_push($body, date('y-m-d'));
            $body['value'] = (float) $body['value'];
            $f3->get('DB')->exec(
                "INSERT INTO orders (`from`, `to`, value, client_name, client_number, date) VALUE (?, ?, ?, ?, ?, ?)",
                array_values($body)
            );
            echo json_encode(array('result'=>'success', 'what'=>'Order was successfully added'));
        } else {
            http_response_code(422);
            echo json_encode(array('result' => 'error', 'what' => $msg));
        }
    }

    public static function validateOrderData($body) {
        $validator = new Validator();
        $keys = ['from', 'to', 'value', 'client_number', 'client_name'];

        if (count($body) !== 5) return "Invalid count of entities\r\n";

        for ($i = 0; $i < count($body); $i++) {
            if (!array_key_exists($keys[$i], $body)) {
                return "Could not found some entities\r\n";
            }
        }
        $result = "";
        $result .= $validator->validateValue($body['value']);
        $result .= $validator->validateCoordinates($body['from']);
        $result .= $validator->validateCoordinates($body['to']);
        $result .= $validator->validateName($body['client_name']);
        $result .= $validator->validatePhone($body['client_number']);

        if ($result != "") {
            return $result;
        } else {
            return true;
        }
    }
}
