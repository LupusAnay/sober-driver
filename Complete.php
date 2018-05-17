<?php
/**
 * Created by PhpStorm.
 * User: Sariel Aki ~desu~
 * Date: 14.05.2018
 * Time: 22:03
 *
 */

class Complete
{
    public static function client(Base $f3)
    {
        if ($f3->get('SESSION.order_id') != null) {
            $result = $f3->get('DB')->exec("UPDATE orders SET client_complete_check = 'true' WHERE id = ? AND status = 'taken'",
                $f3->get('SESSION.order_id')
            );
            if($result != 0) {
                echo json_encode(array("result" => "success", "what" => "Вы подтвердили выполнение заказа"));
            } else {
                http_response_code(405);
                echo json_encode(array('result' => 'error', 'what' => 'Заказ уже подтвержден'));
            }
        }
    }

    public static function driver(Base $f3)
    {
        if ($f3->get('SESSION.order_id') != null) {
            $result = $f3->get('DB')->exec("UPDATE orders SET driver_complete_check = 'true' WHERE id = ?",
                $f3->get('SESSION.order_id')
            );
            if($result != 0) {
                echo json_encode(array("result" => "success", "what" => "Вы подтвердили выполнение заказа"));
            } else {
                http_response_code(405);
                echo json_encode(array('result' => 'error', 'what' => 'Заказ уже подтвержден'));
            }
        }
    }

    static function afterroute(Base $f3)
    {
        $result = $f3->get('DB')->exec("SELECT * FROM orders WHERE client_complete_check = 'true' AND driver_complete_check = 'true'");
        $json_res = json_encode($result);
        $json_code = json_decode($json_res, true);
        $completed_order_data = array($json_code[0]['id'], $json_code[0]['date'], $json_code[0]['value'], $json_code[0]['driver_phone'], $json_code[0]['client_number']);
        if (count($result) != 0) {
            $f3->get('DB')->exec('INSERT INTO executed_orders(order_id, date, value, driver_phone, client_number) VALUE(?, ?, ?, ?, ?)',
                $completed_order_data
            );
            $f3->get('DB')->exec("DELETE FROM orders WHERE client_complete_check = 'true' AND driver_complete_check = 'true'");
        }
    }
}