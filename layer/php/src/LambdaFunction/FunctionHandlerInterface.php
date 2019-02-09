<?php

namespace LambdaPHP\LambdaFunction;


interface FunctionHandlerInterface
{
    public function run(FunctionInterface $function);
}