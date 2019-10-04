<?php

namespace Brighte\Sqs\Tests\Spec;

use Brighte\Sqs\SqsContext;
use Brighte\Sqs\SqsDestination;

trait CreateSqsQueueTrait
{
    private $queue;

    protected function createSqsQueue(SqsContext $context, string $queueName): SqsDestination
    {
        $queueName = $queueName.time();

        $this->queue = $context->createQueue($queueName);
        $context->declareQueue($this->queue);

        return $this->queue;
    }
}
