<?php

namespace LambdaPHP\Functions;

use Aws\Sdk;
use LambdaPHP\Examples\AwsLambdaExamples;
use LambdaPHP\LambdaFunction\FunctionInterface;

class LambdaFunction implements FunctionInterface {

    /**
     * @var array
     */
    private $request;

    private $response;

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

    public function invoke($request = null)
    {
        $this->setRequest($request);

        $awsSdk = new Sdk(AwsLambdaExamples::AWS_SDK_ARGS);

        $awsLambdaExamples = new AwsLambdaExamples($awsSdk, $this->getRequest());
        $awsLambdaExamples->dynamoDbPutObjectFromPayload($this->getPayload());
        $awsLambdaExamples->s3PutObject();

        $this->addToResponse($awsLambdaExamples->getResponse());

        return $this->getResponse();
    }

    public function getPayload()
    {
        return $this->getRequest()['payload'];
    }
}