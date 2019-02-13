<?php

namespace Tests\LambdaFunction;


use LambdaPHP\LambdaFunction\FunctionInterface;
use LambdaPHP\LambdaFunction\LambdaHandler;
use LambdaPHP\LambdaFunction\LocalHandler;
use PHPUnit\Framework\TestCase;

class LambdaFunctionTest extends TestCase {

    public function testLocalHandlerResponse()
    {
        $functionMock = $this->getMockBuilder(FunctionInterface::class)->getMock();
        $functionMock->expects($this->once())
            ->method('invoke')
            ->willReturn([]);

        $handler = new LocalHandler();
        $response = $handler->run($functionMock);

        $this->assertIsArray($response);
    }

    public function testLambdaHandlerResponse()
    {
        $functionMock = $this->getMockBuilder(FunctionInterface::class)->getMock();
        $functionMock->expects($this->once())
            ->method('invoke')
            ->willReturn([]);

        $handler = new LambdaHandler();
        $response = $handler->run($functionMock);

        $this->assertIsArray($response);
    }
}