<?php

namespace LambdaPHP\LambdaFunction;

use InvalidArgumentException;

class LambdaEnv implements EnvInterface {

    /**
     * @var array
     */
    private $env;

    public function __construct(array $env)
    {
        $this->env = $env;
    }

    /**
     * @param string $envName
     * @return string|InvalidArgumentException
     */
    public function getenv($envName)
    {
        if (isset($this->env[$envName]) && !empty($this->env[$envName])) {
            $envValue = $this->env[$envName];
        } else {
            throw new InvalidArgumentException();
        }

        return $envValue;
    }
}