<?php

namespace Micronative\Sqs\Tests\Spec;

use Micronative\Sqs\SqsMessage;
use Interop\Queue\Spec\MessageSpec;

class SqsMessageTest extends MessageSpec
{
    /**
     * {@inheritdoc}
     */
    protected function createMessage()
    {
        return new SqsMessage('');
    }
}
