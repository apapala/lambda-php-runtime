<?php

namespace LambdaPHP\LambdaFunction;

interface LambdaRuntimeInterface {

    public function getNextRequest();
    public function sendResponse($response);
    public function setUp();
    public function getPayload();
    public function getHandler() : string;
    public function getRequest();


}