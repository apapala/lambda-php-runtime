<?php

use LambdaPHP\LambdaFunction\LocalHandler;
use LambdaPHP\LocalFunction;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

$function = new LocalFunction();

$handler = new LocalHandler();
$handler->run($function);

var_dump($function->getResponse());