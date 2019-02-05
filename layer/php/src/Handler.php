<?php

namespace LambdaPHP;

use Aws\Sdk;
use LambdaPHP\LambdaFunction\FunctionInterface;

class Handler {

    public function run(FunctionInterface $function)
    {
        $function->invoke();
    }
}

