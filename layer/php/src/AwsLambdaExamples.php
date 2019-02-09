<?php

namespace LambdaPHP;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
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
     * @var DynamoDbClient
     */
    private $response = [];

    private $sdk;

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
    }

    public function runExamples()
    {
        $this->s3PutObject();
        $this->dynamoDbPutObject(file_get_contents(__DIR__ . '/data/moviedata.json'));

    }

    public function s3PutObject() {

        $response = $this->s3Client->putObject([
            'Bucket' => getenv('AWS_BUCKET_NAME'),
            'Key' => 'test-s3.json',
            'Body' => __DIR__ . '/data/test-s3.json'
        ]);

        $this->addToResponse($response);

        $response = $this->s3Client->putObject([
            'Bucket' => getenv('AWS_BUCKET_NAME'),
            'Key' => 'test-s3.json',
            'Body' => __DIR__ . '/data/horseshoe.jpg'
        ]);

        $this->addToResponse($response);

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

            } catch (DynamoDbException $e) {

                echo "Unable to add movie:\n";
                echo $e->getMessage() . "\n";
                break;

            }
        }
    }

    public function lambdaSendPayload()
    {
        if (false === getenv('AWS_LAMBDA_FUNCTION_NAME')) {
            throw new InvalidArgumentException();
        }

        $lambdaClient = $this->sdk->createLambda([
            'credentials' => [
                'key'    => getenv('AWS_ACCESS_KEY'),
                'secret' => getenv('AWS_SECRET'),
            ]
        ]);

        $movieData = file_get_contents(__DIR__ . '/data/moviedata.json');

        try {
            $result = $lambdaClient->invoke([
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