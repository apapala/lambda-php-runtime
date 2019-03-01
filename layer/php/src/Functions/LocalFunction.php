<?php

namespace LambdaPHP\Functions;

use Aws\Sdk;
use LambdaPHP\Examples\AwsLambdaExamples;
use LambdaPHP\LambdaFunction\AbstractLambdaFunction;

class LocalFunction extends AbstractLambdaFunction
{
    public function getRequest()
    {
        return null;
    }

    public function invoke($request = null)
    {
        $awsSdk = new Sdk(AwsLambdaExamples::AWS_SDK_ARGS);

        $awsLambdaExamples = new AwsLambdaExamples($awsSdk, $this->getRequest());
//        $awsLambdaExamples->runLocalExamples();
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
