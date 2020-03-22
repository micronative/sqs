<?php

namespace Micronative\Sqs\Tests\Spec;

use Micronative\Sqs\SqsContext;
use Micronative\Sqs\SqsDestination;

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
