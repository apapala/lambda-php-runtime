<?php

use LambdaPHP\Handler;
use LambdaPHP\LambdaFunction\LocalFunction;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

function handler()
{
    $localFunction = new LocalFunction();

    $handler = new Handler();
    $handler->run($localFunction);
}

handler();
