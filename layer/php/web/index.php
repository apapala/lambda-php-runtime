<?php

use LambdaPHP\LambdaFunction\LocalHandler;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

$handler = new LocalHandler();
$handler->process();

var_dump($handler->getResponse());