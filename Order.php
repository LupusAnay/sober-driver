<?php
/**
 * Created by PhpStorm.
 * User: Sariel Aki ~desu~
 * Date: 28.02.2018
 * Time: 19:46
 */

class Order extends Main
{
    public static function put(Base $f3)
    {
        $parameter = $f3->get('PARAMS.id');
        $f3->get('DB')->exec("UPDATE orders SET status = 'taken' WHERE id = ?",
            $parameter);
        $f3->set('SESSION.order', $parameter);
    }
    public static function delete(Base $f3)
    {
        if ($f3->get('SESSION.client_order') != null) {
            $f3->get('DB')->exec("DELETE from orders WHERE client_number = ?",
                $f3->get('SESSION.client_order'));
            $f3->clear('SESSION.client_order');
        }
    }

    public static function findAll(Base $f3)
    {
        if($f3->get('SESSION.order') != null) {
            $result = $f3->get('DB')->exec('SELECT * FROM orders WHERE id = ?',
                $f3->get('SESSION.order'));
            if (count($result) != 0) {
                echo json_encode($result);
            } else {
                echo $f3->error('404');
            }

        } else if($f3->get('SESSION.client_order') != null){
            $result = $f3->get('DB')->exec('SELECT * FROM orders WHERE client_number = ?',
                $f3->get('SESSION.client_order'));
            if (count($result) != 0) {
                echo json_encode($result);
            } else {
                echo $f3->error('404');
            }
        } else {
            $result = $f3->get('DB')->exec('SELECT * FROM orders');
            echo json_encode($result);
        }
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
            $f3->set('SESSION.order_value', $body['value']);
            $f3->set('SESSION.client_order', $body['client_number']);
            $result = $f3->get('DB')->exec('SELECT id FROM orders WHERE client_number = ?',
            $f3->get('SESSION.client_order')
            );
            $json_res =  json_encode($result);
            $json_code = json_decode($json_res, true);
            $f3->set('SESSION.order_id', $json_code[0]['id']);
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
