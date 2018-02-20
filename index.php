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
    function () {
        echo 0 + true;
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
$f3->route('POST /registration', 'Authentication::register');

/** @doc
 * Запуск фреймворка
 */
$f3->run();
