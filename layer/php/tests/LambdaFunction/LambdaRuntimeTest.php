<?php

namespace Tests\LambdaFunction;


use GuzzleHttp\Client;
use LambdaPHP\LambdaFunction\EnvInterface;
use LambdaPHP\LambdaFunction\LambdaRuntime;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class LambdaRuntimeTest extends TestCase {

    public function testLambdaNextRequest()
    {
        $guzzleClientMock = $this->getMockBuilder(Client::class)->setMethods(['get'])->getMock();
        $guzzleResponseMock = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $guzzleClientMock->expects($this->once())->method('get')->willReturn($guzzleResponseMock);

        $envInterfaceMock = $this->getMockBuilder(EnvInterface::class)->getMock();
        $envInterfaceMock->expects($this->once())->method('getenv')->willReturn('value-of-environment-variable');

        $guzzleResponseMock->expects($this->once())->method('getStatusCode')->willReturn('200');
        $guzzleResponseMock->expects($this->once())->method('getHeader')->willReturn('lkjshdf87q634sd7djf8');
        $guzzleResponseMock->expects($this->once())->method('getBody')->willReturn('{"glossary":{"title":"exampleglossary","GlossDiv":{"title":"S","GlossList":{}}}}');

        $lambdaRuntime = new LambdaRuntime($guzzleClientMock, $envInterfaceMock);

        $response = $lambdaRuntime->getNextRequest();

        $this->assertIsArray($response);
        $this->assertIsArray($response['payload']);

        array_map(function($k) {

            $this->assertNotEmpty($k);

        }, $response);
    }
}
