<?php
require '../vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$app = new \App\App();
$app->run();
