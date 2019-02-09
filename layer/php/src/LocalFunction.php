<?php

namespace LambdaPHP;

use Aws\Sdk;
use LambdaPHP\LambdaFunction\FunctionInterface;

class LocalFunction implements FunctionInterface {

    private $response;

    public function getRequest()
    {
        return null;
    }

    public function addToResponse($response)
    {
        $this->response[] = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function invoke()
    {
        $awsSdk = new Sdk(AwsLambdaExamples::AWS_SDK_ARGS);

        $awsLambdaExamples = new AwsLambdaExamples($awsSdk, $this->getRequest());
        // $awsLambdaExamples->runExamples();
        $awsLambdaExamples->lambdaSendPayload();

        $this->addToResponse($awsLambdaExamples->getResponse());

        return $awsLambdaExamples->getResponse();
    }
}
