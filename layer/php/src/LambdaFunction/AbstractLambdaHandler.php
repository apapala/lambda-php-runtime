<?php

namespace LambdaPHP\LambdaFunction;

use InvalidArgumentException;

abstract class AbstractLambdaHandler
{

    protected $function;

    protected $class;

    protected $response;

    /**
     * @param LambdaRuntimeInterface|null $runtime
     * @return string
     */
    abstract public function process(LambdaRuntimeInterface $runtime = null);

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