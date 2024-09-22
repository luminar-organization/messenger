<?php

namespace Luminar\Components\Messenger\Tests;

use Luminar\Components\Messenger\Envelope;
use Luminar\Components\Messenger\Interfaces\MessageHandlerInterface;

class SMSHandler implements MessageHandlerInterface
{
    /**
     * @param Envelope $envelope
     * @return bool
     */
    public function handle(Envelope $envelope): bool
    {
        $smsContent = $envelope->getMessage();
        $targetNumber = $envelope->getStamps()['number'];


        // Your own logic to sent SMS to $targetNumber via your own gateway
        echo "Message of content '" . $smsContent . "' has been sent for " . $targetNumber . "\n";

        return $smsContent and $targetNumber;
    }
}