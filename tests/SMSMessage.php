<?php

namespace Luminar\Components\Messenger\Tests;

use DateTime;
use Luminar\Components\Messenger\Envelope;
use Luminar\Components\Messenger\Interfaces\MessageHandlerInterface;
use Luminar\Components\Messenger\Interfaces\MessageInterface;

class SMSMessage implements MessageInterface
{
    protected string $content;
    protected DateTime $available;
    protected string $message;
    protected string $number;
    protected MessageHandlerInterface $handler;

    /**
     * @return MessageHandlerInterface
     */
    public function getHandler(): MessageHandlerInterface
    {
        return $this->handler;
    }

    /**
     * @param MessageHandlerInterface $handler
     */
    public function setHandler(MessageHandlerInterface $handler): void
    {
        $this->handler = $handler;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param string $number
     */
    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        $envelope = new Envelope($this->getMessage(), [
            'number' => $this->getNumber(),
            'handler' => $this->getHandler()
        ]);
        return $envelope->serialize();
    }

    /**
     * @return DateTime
     */
    public function getAvailable(): DateTime
    {
        return $this->available ?? new DateTime();
    }

    /**
     * @param DateTime $available
     */
    public function setAvailable(DateTime $available): void
    {
        $this->available = $available;
    }
}