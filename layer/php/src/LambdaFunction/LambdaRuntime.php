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
    /**
     * @var void
     */
    private $request;

    private $payload;

    /**
     * @var int
     */
    private $invocationId;

    public function __construct(Client $client, EnvInterface $env)
    {
        $this->env = $env;
        $this->client = $client;
    }

    /**
     *
     */
    public function setUp()
    {
        $this->request = $this->getNextRequest();
        $this->handler = $this->env->getenv('_HANDLER');
        $this->payload = $this->request['payload'];
        $this->invocationId = $this->request['invocationId'];
    }

    public function getPayload()
    {
        return $this->payload;
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

    public function sendResponse($response)
    {
        $awsLambdaRuntimeApi = $this->env->getenv('AWS_LAMBDA_RUNTIME_API');

        $this->client->post(
            'http://' . $awsLambdaRuntimeApi . '/2018-06-01/runtime/invocation/' . $this->getInvocationId() . '/response',
            [
                'form_params' => [
                    'body' => $response
                ]
            ]
        );
    }

    public function getHandler() :string
    {
        return $this->handler;
    }

    private function getInvocationId()
    {
        return $this->invocationId;
    }

    public function getRequest()
    {
        return $this->request;
    }
}