<?php namespace LambdaPHP\LambdaFunction;

interface FunctionInterface {

    public function getRequest();
    public function addToResponse($response);
    public function getResponse();
    public function invoke();

}