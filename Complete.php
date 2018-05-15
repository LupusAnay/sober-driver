<?php
/**
 * Created by PhpStorm.
 * User: fulle
 * Date: 14.05.2018
 * Time: 22:03
 */

class Complete
{
    public static function client(Base $f3){
        $values_from_client = [];
        if($f3->get('SESSION.client_order') != null) {
            array_push($values_from_client, $f3->get('SESSION.client_order'), $f3->get('SESSION.order_value'), $f3->get('SESSION.order_id'), date('y-m-d'));
            $f3->get('DB')->exec('INSERT INTO executed_orders(client_number, value, order_id, date) VALUE(?, ?, ?, ?)',
                array_values($values_from_client)
            );
            $f3->get('DB')->exec("UPDATE orders SET client_complete_check = 'true' WHERE client_number = ?",
                $f3->get('SESSION.client_order')
            );
        }
    }
    public static function driver(Base $f3){
        $values_from_driver = [];
        if($f3->get('SESSION.order') != null){
            array_push($values_from_driver, $f3->get('SESSION.driver_phone'), $f3->get('SESSION.order'));
            $f3->get('DB')->exec('UPDATE executed_orders SET driver_phone = ? WHERE order_id = ?',
                array_values($values_from_driver)
            );
            $f3->get('DB')->exec("UPDATE orders SET driver_complete_check = 'true' WHERE id = ?",
                $f3->get('SESSION.order')
            );
        }
    }

    static function afterroute(Base $f3) {
        $f3->get('DB')->exec("DELETE FROM orders WHERE client_complete_check = 'true' AND driver_complete_check = 'true'");
    }
}