<?php

$_M = $_SERVER;

$_M['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';
$_M['SCRIPT_NAME'] = '/index.php';
$_M['PHP_SELF'] = '/index.php';

require __DIR__ . '/../public/index.php';