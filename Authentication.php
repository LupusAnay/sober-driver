<?php
/**
 * Created by PhpStorm.
 * User: lupusanay
 * Date: 19.02.2018
 * Time: 13:51
 */

class Authentication
{
    /** @doc
     * @temp ���������� �������� ���� POST ������� (����� �������� ����� ����� ���������� ������)
     * � ������� ������ ������������
     * @temp �� ������ ������ ����� ���������� ������ � ��, ��� �������� �������������� ���������
     */
    public static function register(Base $f3, $params) {
        $body = json_decode($f3->get('BODY'));
        $data_invalid = false;
        //TODO ������� ��������� ������� ������ ��� ����������� � ��������� ������ (mb �����)
        foreach($body as $key => $value) {
            if($key == "first_name" or $key == "second_name") {
                if(!preg_match('A-Za-z�-��-�', $value)) {
                    $data_invalid = true;
                    break;
                }
                if(strlen($value) <= 16 and strlen($value) > 1) {
                    $value = ucwords($value);
                } else {
                    $data_invalid = true;
                    break;
                }
            }
        }
    }

    public static function login() {
        //TODO ������� ���
    }

}