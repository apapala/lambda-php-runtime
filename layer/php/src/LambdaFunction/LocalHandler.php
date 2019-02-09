<?php

namespace LambdaPHP\LambdaFunction;


class LocalHandler implements FunctionHandlerInterface
{
    public function run(FunctionInterface $function)
    {
        $function->invoke();
    }
}