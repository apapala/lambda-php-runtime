<?php

namespace LambdaPHP\LambdaFunction;


interface FunctionHandlerInterface
{
    public function process(LambdaRuntimeInterface $runtime = null);

    public function getClass();

    public function getFunction();

    public function getResponse();
}