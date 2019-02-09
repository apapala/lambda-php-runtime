<?php

namespace LambdaPHP\LambdaFunction;


class LambdaHandler implements FunctionHandlerInterface {

    public function run(FunctionInterface $function)
    {
        $function->invoke();
    }
}

