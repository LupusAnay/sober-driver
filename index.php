<?php
/**
 * @author lupusanay
 * @date 19.02.2018
 * @description ���� ������������� FatFree � ����������� ������������� � ������������ � ��.
 */

/** @doc
 * Access-Control-Allow-Origin (��������� ����������� ������� � ������� �� ��������� �������)
 */
header("Access-Control-Allow-Origin: *");

/** @doc
 * ����������� ������������� ����� ���������� FatFree
 */
$f3 = require('lib/base.php');

/** @doc
 * ������������� ��������� ������� � ��������� ������ PCRE
 */
$f3->set('DEBUG', 1);
if ((float)PCRE_VERSION < 7.9)
    trigger_error('PCRE version is out of date');

/** @doc
 * ���������� ���������� ���������� �� config.ini. � ��� ����� �������� ������ ��� ������
 * @uses $f3->get(@var_name)
 */
$f3->config('config.ini');

/** @doc
 * �������� ������ �� ���������� ���������� ������������ ����� $f3->config() � ������� �� �� ������
 * ������ - ��������� ��� ������������ ������������ � ����������� ������-����������� ���������� � MySQL ��
 */
$dsn = 'mysql:host=' . $f3->get('DBHOST') . ';port=' . $f3->get('DBPORT') . ';dbname=' . $f3->get('DBNAME');
$user = $f3->get('DBUSER');
$pw = $f3->get('DBPASSWORD');

$f3->set('DB', new DB\SQL($dsn, $user, $pw));

/** @doc
 * ������������ �������������
 */

//$f3->route('GET /',
//    function () {
//        $str = 'fuck';
//        if(0 == false) {
//            echo ('0 == false');
//        } else echo ('0 != false');
//        echo $str;
//    }
//);

//$f3->route('GET /db',
//    function () use ($f3) {
//        header('Content-Type: application/json; charset=utf-8');
//        $result = $f3->get('DB')->exec('SELECT * FROM local_base_for_testing.for_testing');
//        echo json_encode($result);
//    }
//);

/** @doc
 * ������������ ������������� ��� �����������. �������� ����������� ����� register() ������ Authentication � ������
 * �������� ��� ��������� $f3 � $params
 */
$f3->route('POST /registration', 'Authentication::register');

/** @doc
 * ������ ����������
 */
$f3->run();
