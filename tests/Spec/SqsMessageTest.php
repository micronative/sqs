<?php

namespace Brighte\Sqs\Tests\Spec;

use Brighte\Sqs\SqsMessage;
use Interop\Queue\Spec\MessageSpec;

class SqsMessageTest extends MessageSpec
{
    /**
     * {@inheritdoc}
     */
    protected function createMessage()
    {
        return new SqsMessage();
    }
}
