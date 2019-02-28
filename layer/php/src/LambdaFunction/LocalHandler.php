<?php

namespace LambdaPHP\LambdaFunction;


class LocalHandler implements FunctionHandlerInterface
{
    private $response;

    public function process(LambdaRuntimeInterface $runtime = null)
    {
        $className = sprintf('LambdaPHP\Functions\%s', $this->getClass());
        return $this->response = call_user_func([new $className, $this->getFunction()]);
    }

    public function getClass()
    {
        return 'LocalFunction';
    }

    public function getFunction()
    {
        return 'invoke';
    }

    public function getResponse()
    {
        return $this->response;
    }
}