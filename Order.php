<?php
/**
 * Created by PhpStorm.
 * User: Sariel Aki ~desu~
 * Date: 28.02.2018
 * Time: 19:46
 */

class Order
{
    public static function put(Base $f3)
    {
        $parameters = [];
        $req_parameter = $f3->get('PARAMS.id');
        array_push($parameters, $f3->get('SESSION.driver_phone'), $req_parameter);
        $f3->get('DB')->exec("UPDATE orders SET status = 'taken', driver_phone = ? WHERE id = ?",
            $parameters);
        $f3->set('SESSION.order_id', $req_parameter);
    }

    public static function delete(Base $f3)
    {
        if ($f3->get('SESSION.order_id') != null) {
            $f3->get('DB')->exec("DELETE from orders WHERE id = ?",
                $f3->get('SESSION.order_id'));
            $f3->clear('SESSION.session_type');
            $f3->clear('SESSION.order_id');
        }
    }

    public static function findAll(Base $f3)
    {
        if ($f3->get('SESSION.order_id') != null) {
            $result = $f3->get('DB')->exec("SELECT * FROM orders WHERE  id = ?",
                $f3->get('SESSION.order_id'));
            $result_with_complete = $f3->get('DB')->exec("SELECT * FROM orders WHERE  id = ? AND status = 'complete'",
                $f3->get('SESSION.order_id'));
            if (count($result) != 0) {
                echo json_encode($result);
            } else if(count($result_with_complete) != 0) {
                $f3->clear('SESSION.order_id');
                http_response_code(403);
                echo json_encode(array('result' => 'error', 'what' => 'Заказ уже удален'));
            } else {
                $f3->clear('SESSION.order_id');
                http_response_code(404);
                echo json_encode(array('result' => 'error', 'what' => 'Заказ не найден'));
            }
        }
        else if ($f3->get('SESSION.session_type') === 'driver') {
            $result = $f3->get('DB')->exec("SELECT * FROM orders WHERE status = 'ready'");
            echo json_encode($result);
        } else {
            http_response_code(403);
            echo json_encode(array('result' => 'error', 'what' => 'Необходимо создать заказ'));
        }
    }

    public static function addNewOrder(Base $f3)
    {
        $body = json_decode($f3->get('BODY'), true);
        $body['value'] = (float)$body['value'];
        $msg = Order::validateOrderData($body);
        if ($msg === true) {
            array_push($body, date('y-m-d'));
            $body['value'] = (float)$body['value'];
            $f3->get('DB')->exec(
                "INSERT INTO orders (`from`, `to`, value, client_name, client_number, date) VALUE (?, ?, ?, ?, ?, ?)",
                array_values($body)
            );
            $f3->set('SESSION.order_value', $body['value']);
            $f3->set('SESSION.client_phone', $body['client_number']);
            $f3->set('SESSION.session_type', 'client');
            $result = $f3->get('DB')->exec("SELECT id FROM orders WHERE client_number = ? AND status = 'ready'",
                $f3->get('SESSION.client_phone')
            );
            $json_res = json_encode($result);
            $json_code = json_decode($json_res, true);
            $f3->set('SESSION.order_id', $json_code[0]['id']);
            echo json_encode(array('result' => 'success', 'what' => 'Заказ был успешно добавлен'));
        } else {
            http_response_code(422);
            echo json_encode(array('result' => 'error', 'what' => $msg));
        }
    }

    public static function validateOrderData($body)
    {
        $validator = new Validator();
        $keys = ['from', 'to', 'value', 'client_number', 'client_name'];

        if (count($body) !== 5) return "Неверное количество элементов\r\n";

        for ($i = 0; $i < count($body); $i++) {
            if (!array_key_exists($keys[$i], $body)) {
                return "Некоторые элементы не найдены\r\n";
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
