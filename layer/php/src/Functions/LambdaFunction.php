<?php

namespace LambdaPHP\Functions;

use Aws\Sdk;
use LambdaPHP\Examples\AwsLambdaExamples;
use LambdaPHP\LambdaFunction\AbstractLambdaFunction;

class LambdaFunction extends AbstractLambdaFunction {

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
}