<?php

// Kickstart the framework
$f3 = require('lib/base.php');

$f3->set('DEBUG', 1);
if ((float)PCRE_VERSION < 7.9)
    trigger_error('PCRE version is out of date');

// Load configuration
$f3->config('config.ini');

$f3->set('DB',
    new DB\SQL (
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
    function () {
        echo('hello, it\'s db');
    }
);

$f3->run();
