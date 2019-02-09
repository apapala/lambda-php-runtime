<?php

namespace LambdaPHP\LambdaFunction;


interface LambdaRuntimeInterface {


    function getNextRequest();
    function sendResponse($invocationId, $response);
}