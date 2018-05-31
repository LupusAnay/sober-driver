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
            $result = $f3->get('DB')->exec("UPDATE orders SET driver_complete_check = 'true' WHERE id = ? AND status = 'taken'",
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
        $f3->get('DB')->exec("UPDATE orders SET status = 'complete' WHERE client_complete_check = 'true' AND driver_complete_check = 'true'");
    }
}