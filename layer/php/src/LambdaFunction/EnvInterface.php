<?php namespace LambdaPHP\LambdaFunction;

use InvalidArgumentException;

interface EnvInterface {

    /**
     * @param string $envName
     * @return string|InvalidArgumentException
     */
    public function getenv($envName);

}