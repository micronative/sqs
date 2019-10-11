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
+ Move all classes to src
+ Rename Tests to tests
+ Move examples to tests
+ Change namespace to Brighte\Sqs

SqsProducer->send(): 
<pre>
public function send(Destination $destination, Message $message): void
    {
        InvalidDestinationException::assertDestinationInstanceOf($destination, SqsDestination::class);
        InvalidMessageException::assertMessageInstanceOf($message, SqsMessage::class);

        $body = $message->getBody();
        if (empty($body)) {
            throw new InvalidMessageException('The message body must be a non-empty string.');
        }

        $arguments = [
            '@region' => $destination->getRegion(),
            'MessageBody' => $body,
            'QueueUrl' => $this->context->getQueueUrl($destination),
        ];

        if (null !== $this->deliveryDelay) {
            $arguments['DelaySeconds'] = (int) $this->deliveryDelay / 1000;
        }

        if ($message->getDelaySeconds()) {
            $arguments['DelaySeconds'] = $message->getDelaySeconds();
        }

        if ($message->getMessageDeduplicationId()) {
            $arguments['MessageDeduplicationId'] = $message->getMessageDeduplicationId();
        }

        if ($message->getMessageGroupId()) {
            $arguments['MessageGroupId'] = $message->getMessageGroupId();
        }

        if ($message->getHeaders()) {
            $arguments['MessageAttributes']['Headers'] = [
                'DataType' => 'String',
                'StringValue' => json_encode([$message->getHeaders()]),
            ];
        }
        
        if ($message->getProperties()) {
            foreach ($message->getProperties() as $name => $value) {
                $arguments['MessageAttributes'][$name] = ['DataType' => 'String', 'StringValue' => $value];
            }
        }

        $result = $this->context->getSqsClient()->sendMessage($arguments);

        if (false == $result->hasKey('MessageId')) {
            throw new \RuntimeException('Message was not sent');
        }

        $message->setMessageId($result['MessageId']);
    }
</pre>

SqsConsumer->covertMessage():
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
            foreach ($sqsMessage['MessageAttributes'] as $name => $attribute) {
                if ($name == 'Headers') {
                    $headers = json_decode($attribute['StringValue'], true);
                    $message->setHeaders($headers);
                } else {
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
