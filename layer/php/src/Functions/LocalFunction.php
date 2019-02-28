<?php

namespace LambdaPHP\Functions;

use Aws\Sdk;
use LambdaPHP\AwsLambdaExamples;
use LambdaPHP\LambdaFunction\FunctionInterface;

class LocalFunction implements FunctionInterface {

    /**
     * @var array
     */
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
        // $awsLambdaExamples->runLocalExamples();
        $awsLambdaExamples->lambdaSendPayload(file_get_contents(__DIR__ . '/../data/moviedata.json'));
//        $awsLambdaExamples->s3PutObject();

        $this->addToResponse($awsLambdaExamples->getResponse());

        return $awsLambdaExamples->getResponse();
    }

    public function getPayload()
    {
        return null;
    }
}
