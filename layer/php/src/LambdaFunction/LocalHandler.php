<?php

namespace LambdaPHP\LambdaFunction;


class LocalHandler implements FunctionHandlerInterface
{
    /**
     * @param FunctionInterface $function
     * @return array
     */
    public function run(FunctionInterface $function)
    {
        return $function->invoke();
    }
}