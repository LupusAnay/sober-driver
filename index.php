<?php
/**
 * @author lupusanay
 * @date 19.02.2018
 * @description Файл инициализации FatFree с параметрами маршрутизации и подключением к БД.
 */

/** @doc
 * Access-Control-Allow-Origin (разрешает производить запросы к ресурсу со сторонних доменов)
 */
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Content-Type");
header('Access-Control-Allow-Credentials: true');
/** @doc
 * Подключение заголовочного файла фреймворка FatFree
 */
$f3 = require('lib/base.php');
/** @doc
 * Устанавливает параметры отладки и проверяет версию PCRE
 */
$f3->set('DEBUG', 1);
if ((float)PCRE_VERSION < 7.9)
    trigger_error('PCRE version is out of date');

/** @doc
 * Подключает глобальные переменные из config.ini. К ним можно получить доступ при помощи
 * @uses $f3->get(@var_name)
 */
$f3->config('config.ini');

/** @doc
 * Забирает данные из глобальных переменных подключенных через $f3->config() и создает на их основе
 * строки - параметры для последующего подставления в конструктор класса-дескриптора соединения с MySQL БД
 */
$dsn = 'mysql:host=' . $f3->get('DBHOST') . ';port=' . $f3->get('DBPORT') . ';dbname=' . $f3->get('DBNAME');
$user = $f3->get('DBUSER');
$pw = $f3->get('DBPASSWORD');

$f3->set('DB', new DB\SQL($dsn, $user, $pw));

/** @doc
 * Конфигурация маршрутизации
 */

$f3->route('GET /',
    function () use ($f3) {
        $db = $f3->get('DB');
        $result = $f3->get('DB')->exec('SELECT password FROM employees WHERE phone = \'+79119119191\'');
        echo $result[0]['password'];
    }
);

class Main
{
    /*static function beforeroute(Base $f3)
    {
       if($f3->get('SESSION.session_type') === 'client') {

       }
    }*/
}


$f3->route('GET /session',
    function () use ($f3) {
        $session_array = array(
            'order_id' => $f3->get('SESSION.order_id'),
            'session_type' => $f3->get('SESSION.session_type')
        );
        echo json_encode($session_array);
    }
);
$f3->route('GET /kill',
    function () use ($f3) {
        if ($f3->get('SESSION.order_id') != null) {
            if ($f3->get('SESSION.session_type') === 'driver') {
                $f3->get('DB')->exec("UPDATE orders SET driver_complete_check = 'false' status = 'ready', driver_phone = 'null' WHERE id = ?",
                    $f3->get('SESSION.order_id')
                );
                $f3->clear('SESSION');
            } else if ($f3->get('SESSION.session_type') === 'client') {
                $f3->get('DB')->exec("DELETE FROM orders WHERE id = ?",
                    $f3->get('SESSION.order_id')
                );
                $f3->clear('SESSION');
            }
        } else {
            $f3->clear('SESSION');
        }
    }
);
$f3->route('GET /order_complete',
    function () use ($f3) {
        $f3->clear('SESSION.order_id');
    }
);

//$f3->route('GET /db',
//    function () use ($f3) {
//        header('Content-Type: application/json; charset=utf-8');
//        $result = $f3->get('DB')->exec('SELECT * FROM local_base_for_testing.for_testing');
//        echo json_encode($result);
//    }
//);

/** @doc
 * Конфигурация маршрутизации для регистрации. Вызывает статический метод register() класса Authentication и неявно
 * передает ему параметры $f3 и $params
 */
header('Content-Type: application/json; charset=utf-8');
$f3->route('POST /login', 'Authentication::login');
$f3->route('POST /registration', 'Authentication::register');
//$f3->map('/order', 'Order'); TODO: Закодить нормальный map
$f3->route('PUT /take_order/@id', 'Order::put');
$f3->route('DELETE /del_order', 'Order::delete');
$f3->route('GET /orders', 'Order::findAll');
$f3->route('POST /addOrder', 'Order::addNewOrder');
$f3->route('GET /driver', 'Complete::driver');
$f3->route('GET /client', 'Complete::client');
/** @doc
 * Запуск фреймворка
 */
$f3->run();
