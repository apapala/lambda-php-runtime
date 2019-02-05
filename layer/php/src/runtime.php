<?php

use LambdaPHP\Handler;
use LambdaPHP\LambdaFunction\FunctionInterface;
use LambdaPHP\LambdaFunction\LambdaFunction;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

function getNextRequest()
{
    $client = new \GuzzleHttp\Client();
    $response = $client->get('http://' . $_ENV['AWS_LAMBDA_RUNTIME_API'] . '/2018-06-01/runtime/invocation/next');

    return [
        'code' => $response->getStatusCode(),
        'invocationId' => $response->getHeader('Lambda-Runtime-Aws-Request-Id')[0],
        'payload' => json_decode((string) $response->getBody(), true)
    ];
}

function sendResponse($invocationId, $response)
{
    $client = new \GuzzleHttp\Client();
    $client->post(
        'http://' . $_ENV['AWS_LAMBDA_RUNTIME_API'] . '/2018-06-01/runtime/invocation/' . $invocationId . '/response',
        [
            'form_params' => [
                'body' => $response
            ]
        ]
    );
}

function handler(FunctionInterface $lambdaFunction)
{

    $handler = new Handler();
    $handler->run($lambdaFunction);

    return $lambdaFunction->getResponse();
}

// This is the request processing loop. Barring unrecoverable failure, this loop runs until the environment shuts down.
do {
    // Ask the runtime API for a request to handle.
    $request = getNextRequest();

    $response = [];

    $output['_HANDLER'] = $_ENV['_HANDLER'];
    $output['env'] = $_ENV;
    $output['server'] = $_SERVER;
    $output['request'] = $request;

    // Obtain the function name from the _HANDLER environment variable and ensure the function's code is available.
    // $handlerFunction = array_slice(explode('.', $_ENV['_HANDLER']), -1)[0];
    // require_once '/opt/src/' . $handlerFunction . '.php';

    $lambdaFunction = new LambdaFunction($request);

    // Execute the desired function and obtain the response.
    $response = handler($lambdaFunction);

    $output['response'] = $response;

    // Submit the response back to the runtime API.
    sendResponse($request['invocationId'], $output);
} while (true);
