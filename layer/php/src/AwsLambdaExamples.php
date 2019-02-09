<?php

namespace LambdaPHP;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Aws\Lambda\LambdaClient;
use Aws\Sdk;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

class AwsLambdaExamples {

    const AWS_SDK_ARGS = [
        'region'   => 'us-east-1',
        'version'  => 'latest'
    ];

    /**
     * @var \Aws\Sdk
     */
    private $s3Client;

    /**
     * @var DynamoDbClient
     */
    private $dynamoDbClient;

    /**
     * @var []
     */
    private $response = [];

    /**
     * @var Sdk
     */
    private $sdk;

    /**
     * @var LambdaClient
     */
    private $lambdaClient;

    public function __construct(Sdk $sdk, $request = null) {

        $this->addToResponse($request);
        $this->sdk = $sdk;
        $this->initAwsSdk($sdk);
    }

    public function initAwsSdk(Sdk $sdk)
    {
        $this->s3Client = $sdk->createS3([
            'region' => 'us-east-1',
            'version' => 'latest',
            'credentials' => [
                'key'    => getenv('AWS_ACCESS_KEY'),
                'secret' => getenv('AWS_SECRET'),
            ]
        ]);

        $this->dynamoDbClient = $sdk->createDynamoDb([
            'credentials' => [
                'key'    => getenv('AWS_ACCESS_KEY'),
                'secret' => getenv('AWS_SECRET'),
            ]
        ]);

        $this->lambdaClient = $this->sdk->createLambda([
            'credentials' => [
                'key'    => getenv('AWS_ACCESS_KEY'),
                'secret' => getenv('AWS_SECRET'),
            ]
        ]);
    }

    public function runLocalExamples()
    {
        $this->s3PutObject();
        $this->dynamoDbPutObject(json_decode(file_get_contents(__DIR__ . '/data/moviedata.json'), true));

    }

    /**
     * @throws InvalidArgumentException
     * @param string|array $env
     */
    private function checkEnv($env)
    {
        if (is_string($env)) {
            $env = [$env];
        }

        foreach ($env as $value) {
            if (false === getenv($value)) {
                throw new InvalidArgumentException();
            }
        }
    }

    public function s3PutObject() {

        $this->checkEnv('AWS_BUCKET_NAME');

        try {

            $response = $this->s3Client->putObject([
                'Bucket' => getenv('AWS_BUCKET_NAME'),
                'Key' => 'test-s3.json',
                'Body' => __DIR__ . '/data/test-s3.json'
            ]);

            $this->addToResponse($response);

        } catch (\Exception $exception) {
            $this->addToResponse($exception->getMessage());
        }

    }

    public function dynamoDbPutObjectFromPayload($payload)
    {
        $this->dynamoDbPutObject($payload);
    }

    public function addToResponse($response)
    {
        $this->response[] = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function dynamoDbPutObject($movies)
    {

        $this->checkEnv('AWS_DYNAMODB_TABLENAME');

        $marshaler = new Marshaler();

        $tableName = getenv('AWS_DYNAMODB_TABLENAME');

        foreach ($movies as $movie) {

            $year = $movie['year'];
            $title = $movie['title'];
            $info = $movie['info'];

            $json = json_encode([
                'movieId' => Uuid::uuid4()->toString(),
                'year' => $year,
                'title' => $title,
                'info' => $info,
                'createdAt' => date("Y-m-d H:i:s")
            ]);

            $params = [
                'TableName' => $tableName,
                'Item' => $marshaler->marshalJson($json)
            ];

            try {

                $result = $this->dynamoDbClient->putItem($params);
                $this->addToResponse("Added movie: " . $movie['year'] . " " . $movie['title']);
                $this->addToResponse($result);

            } catch (DynamoDbException $exception) {

                $this->addToResponse($exception->getMessage());

            }
        }
    }

    public function lambdaSendPayload()
    {
        $this->checkEnv(['AWS_LAMBDA_FUNCTION_NAME', 'AWS_ACCESS_KEY', 'AWS_SECRET']);

        $movieData = file_get_contents(__DIR__ . '/data/moviedata.json');

        try {
            $result = $this->lambdaClient->invoke([
                'FunctionName' => getenv('AWS_LAMBDA_FUNCTION_NAME'),
                // 'InvocationType' => 'RequestResponse',
                'Payload' => $movieData
            ]);

            $this->addToResponse($result);
        } catch (\Exception $exception) {

            $this->addToResponse("There was an error: " . $exception->getMessage());
        }
    }
}