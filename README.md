# Brighte Sqs
[![Software license][ico-license]](LICENSE)
[![Version][ico-version-stable]][link-packagist]
[![Download][ico-downloads-monthly]][link-downloads]
[![Build status][ico-travis]][link-travis]
[![Coverage][ico-codecov]][link-codecov]


[ico-license]: https://img.shields.io/github/license/nrk/predis.svg
[ico-version-stable]: https://img.shields.io/packagist/v/brightecapital/sqs.svg
[ico-downloads-monthly]: https://img.shields.io/packagist/dm/brightecapital/sqs.svg
[ico-travis]: https://travis-ci.com/brighte-capital/sqs.svg?branch=master
[ico-codecov]: https://codecov.io/gh/brighte-capital/sqs/branch/master/graph/badge.svg

[link-packagist]: https://packagist.org/packages/brightecapital/sqs
[link-codecov]: https://codecov.io/gh/brighte-capital/sqs
[link-travis]: https://travis-ci.com/brighte-capital/sqs
[link-downloads]: https://packagist.org/packages/brightecapital/sqs/stats

# Description

This project was forked from [enqueue/sqs](https://github.com/php-enqueue/sqs) and made the following improvements:
+ SqsProducer->send():  
<pre>
$message->setMessageId($result['MessageId']);
</pre>
+ SqsConsumer->covertMessage():
<pre>
protected function convertMessage(array $sqsMessage): SqsMessage
    {
        $message = $this->context->createMessage();

        $message->setBody($sqsMessage['Body']);
        $message->setReceiptHandle($sqsMessage['ReceiptHandle']);

        if (isset($sqsMessage['Attributes'])) {
            $message->setAttributes($sqsMessage['Attributes']);

            if (isset($sqsMessage['Attributes']['MessageDeduplicationId'])) {
                $message->setMessageDeduplicationId($sqsMessage['Attributes']['MessageDeduplicationId']);
            }

            if (isset($sqsMessage['Attributes']['MessageGroupId'])) {
                $message->setMessageGroupId($sqsMessage['Attributes']['MessageGroupId']);
            }
        }

        if (isset($sqsMessage['Attributes']['ApproximateReceiveCount'])) {
            $message->setRedelivered(((int) $sqsMessage['Attributes']['ApproximateReceiveCount']) > 1);
        }

        if (isset($sqsMessage['MessageAttributes'])) {
            if (isset($sqsMessage['MessageAttributes']['Headers'])) {
                $headers = json_decode($sqsMessage['MessageAttributes']['Headers']['StringValue'], true);

                $message->setHeaders($headers[0]);
                $message->setProperties($headers[1]);
            }

            foreach ($sqsMessage['MessageAttributes'] as $name => $attribute) {
                if (isset($attribute['StringValue'])) {
                    $message->setProperty($name, $attribute['StringValue']);
                }
            }
        }

        if (isset($sqsMessage['MessageId'])) {
            $message->setMessageId($sqsMessage['MessageId']);
        }

        return $message;
    }
</pre>
+ Move all classes to src
+ Rename Tests to tests
+ Move examples to tests
+ Change namespace to Brighte\Sqs
