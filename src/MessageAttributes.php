<?php

namespace Luminar\Components\Messenger;

class MessageAttributes
{
    public const STATUS_NONE = 0;
    public const STATUS_IN_QUEUE = 1;
    public const STATUS_IN_DELIVERY = 2;
    public const STATUS_DELIVERED = 3;
    public const STATUS_FAILED = 4;
}