<?php

namespace LambdaPHP\LambdaFunction;


use InvalidArgumentException;

class LambdaHandler implements FunctionHandlerInterface {

    private $function;

    private $class;

    private $response;

    /**
     * @param LambdaRuntimeInterface|null $runtime
     * @return string
     */
    public function process(LambdaRuntimeInterface $runtime = null)
    {
        $handler = $runtime->getHandler();

        $parts =  explode('.', $handler);
        $this->class = $parts[0];
        $this->function = $parts[1];

        $className = sprintf('LambdaPHP\Functions\%s', $this->getClass());

        $this->response = call_user_func([new $className, $this->getFunction()], $runtime->getRequest());

        return $this->response;
    }

    public function getClass()
    {
        if (empty($this->class)) {
            throw new InvalidArgumentException();
        }

        return $this->class;
    }

    public function getFunction()
    {
        if (empty($this->function)) {
            throw new InvalidArgumentException();
        }

        return $this->function;
    }

    public function getResponse()
    {
        return $this->response;
    }
}

