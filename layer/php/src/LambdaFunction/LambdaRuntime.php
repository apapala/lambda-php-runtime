<?php
namespace LambdaPHP\LambdaFunction;

use GuzzleHttp\Client;

class LambdaRuntime implements LambdaRuntimeInterface
{
    /**
     * @var EnvInterface
     */
    private $env;

    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client, EnvInterface $env)
    {
        $this->env = $env;
        $this->client = $client;
    }

    public function getNextRequest()
    {
        $awsLambdaRuntimeApi = $this->env->getenv('AWS_LAMBDA_RUNTIME_API');

        $response = $this->client->get('http://' . $awsLambdaRuntimeApi . '/2018-06-01/runtime/invocation/next');

        return [
            'code' => $response->getStatusCode(),
            'invocationId' => $response->getHeader('Lambda-Runtime-Aws-Request-Id')[0],
            'payload' => json_decode((string) $response->getBody(), true)
        ];
    }

    public function sendResponse($invocationId, $response)
    {
        $awsLambdaRuntimeApi = $this->env->getenv('AWS_LAMBDA_RUNTIME_API');

        $this->client->post(
            'http://' . $awsLambdaRuntimeApi . '/2018-06-01/runtime/invocation/' . $invocationId . '/response',
            [
                'form_params' => [
                    'body' => $response
                ]
            ]
        );

    }
}