<?php
namespace LambdaPHP\LambdaFunction;

use GuzzleHttp\Client;

class LambdaRuntime implements LambdaRuntimeInterface {

    function getNextRequest()
    {
        $client = new Client();
        $response = $client->get('http://' . $_ENV['AWS_LAMBDA_RUNTIME_API'] . '/2018-06-01/runtime/invocation/next');

        return [
            'code' => $response->getStatusCode(),
            'invocationId' => $response->getHeader('Lambda-Runtime-Aws-Request-Id')[0],
            'payload' => json_decode((string) $response->getBody(), true)
        ];
    }

    function sendResponse($invocationId, $response)
    {

        $client = new Client();
        $client->post(
            'http://' . $_ENV['AWS_LAMBDA_RUNTIME_API'] . '/2018-06-01/runtime/invocation/' . $invocationId . '/response',
            [
                'form_params' => [
                    'body' => $response
                ]
            ]
        );
    }
}