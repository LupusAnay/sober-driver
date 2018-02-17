<?php

// Kickstart the framework
$f3 = require('lib/base.php');

$f3->set('DEBUG', 1);
if ((float)PCRE_VERSION < 7.9)
    trigger_error('PCRE version is out of date');

// Load configuration
$f3->config('config.ini');

$f3->set('DB', new DB\SQL(
    'mysql:host=localhost;port=3306;dbname=local_base_for_testing',
    'root',
    ''
    )
);

$f3->route('GET /',
    function () {
        echo('hello, it\'s root');
    }
);

$f3->route('GET /db',
    function () use ($f3) {
        $result = $f3->get('DB')->exec('SELECT * FROM local_base_for_testing.for_testing');
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: *");
        echo json_encode($result);
    }
);

$f3->run();
