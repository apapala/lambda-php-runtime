<?php

namespace LambdaPHP;

use Aws\Sdk;
use LambdaPHP\LambdaFunction\FunctionInterface;

class LambdaFunction implements FunctionInterface {

    private $request;
    private $response;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function addToResponse($response)
    {
        $this->response[] = $response;
    }

    public function getPayload()
    {
        return $this->request['payload'];
    }

    public function invoke()
    {
        $awsSdk = new Sdk(AwsLambdaExamples::AWS_SDK_ARGS);

        $awsLambdaExamples = new AwsLambdaExamples($awsSdk, $this->getRequest());
        $awsLambdaExamples->dynamoDbPutObjectFromPayload($this->getPayload());
        $awsLambdaExamples->s3PutObject();

        $this->addToResponse($awsLambdaExamples->getResponse());
    }
}