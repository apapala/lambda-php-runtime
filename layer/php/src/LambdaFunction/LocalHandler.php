<?php

namespace LambdaPHP\LambdaFunction;

class LocalHandler extends AbstractLambdaHandler
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
}