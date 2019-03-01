<?php namespace LambdaPHP\LambdaFunction;


abstract class AbstractLambdaFunction
{
    abstract public function invoke($request = null);

    /**
     * @var array
     */
    private $request;

    /**
     * @var array
     */
    private $response;

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function addToResponse($response)
    {
        $this->response[] = $response;
    }

    public function getPayload()
    {
        return $this->getRequest()['payload'];
    }
}