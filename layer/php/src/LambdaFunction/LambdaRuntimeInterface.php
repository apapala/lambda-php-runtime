<?php

namespace LambdaPHP\LambdaFunction;


use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

interface LambdaRuntimeInterface {

    public function getNextRequest();
    public function sendResponse($invocationId, $response);
}