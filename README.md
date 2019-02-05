# Lambda with PHP runtime environment

### Installation
First configure your iam user in AWS Console and give him `iam-policy.json` policy.

Create required AWS resources in console:

- S3 bucket
- DynamoDB table

Configure .env with Access Key and Secret, S3 bucket DynamoDB and rest

Install serverless framework that will give you nice set of tools to control your lambda function
```
npm install -g serverless
```

Deploy your first lambda function with php runtime environment
```
sls deploy
```

Invoke function through command line
```
sls invoke -f hello
```
#### Docker env
This repository is using [dev-env-client](https://github.com/apapala/dev-env-client) and [dev-env-host](https://github.com/apapala/dev-env-host) 

Configure `docker/php/hosts.conf` along with `docker-compose.yml`

Start with
```
docker-composer up
```
And then visit [projectname.local](http://projectname.local) in your browser

### Debugging

Bootstrap and binaries files must be executable

### Errors and Problems

S3 bucket is not deleted when executing stack removal from CloudFormation console

PHP SDK don't have access to other AWS services through Lambda and AssumeRole, had to hardcode access key and secret into layer 

### More reading

- https://aws.amazon.com/blogs/apn/aws-lambda-custom-runtime-for-php-a-practical-example/
- https://docs.aws.amazon.com/lambda/latest/dg/runtimes-api.html
- https://docs.aws.amazon.com/lambda/latest/dg/current-supported-versions.html
