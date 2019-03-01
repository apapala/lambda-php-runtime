<?php

namespace LambdaPHP\LambdaFunction;

class LambdaHandler extends AbstractLambdaHandler
{

    /**
     * @param LambdaRuntimeInterface|null $runtime
     * @return string
     */
    public function process(LambdaRuntimeInterface $runtime = null)
    {
        $handler = $runtime->getHandler();

        $parts = explode('.', $handler);
        $this->class = $parts[0];
        $this->function = $parts[1];

        $className = sprintf('LambdaPHP\Functions\%s', $this->getClass());

        $this->response = call_user_func([new $className, $this->getFunction()], $runtime->getRequest());

        return $this->getResponse();
    }

}

