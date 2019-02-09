<?php

use LambdaPHP\LambdaFunction;
use LambdaPHP\LambdaFunction\LambdaHandler;
use LambdaPHP\LambdaFunction\LambdaRuntime;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

// This is the request processing loop. Barring unrecoverable failure, this loop runs until the environment shuts down.
do {

    $runtime = new LambdaRuntime();

    // Ask the runtime API for a request to handle.
    $request = $runtime->getNextRequest();

    $output['_HANDLER'] = $_ENV['_HANDLER'];
    $output['env'] = $_ENV;
    $output['server'] = $_SERVER;
    $output['request'] = $request;

    $function = new LambdaFunction($request);
    $handler = new LambdaHandler();
    $handler->run($function);

    $response = $function->getResponse();

    // Obtain the function name from the _HANDLER environment variable and ensure the function's code is available.
    // $handlerFunction = array_slice(explode('.', $_ENV['_HANDLER']), -1)[0];
    // require_once '/opt/src/' . $handlerFunction . '.php';

    // $lambdaFunction = new LambdaFunction($request);

    // Execute the desired function and obtain the response.
    // $response = handler($lambdaFunction);

    $output['response'] = $response;

    // Submit the response back to the runtime API.
    $runtime->sendResponse($request['invocationId'], $output);
} while (true);
