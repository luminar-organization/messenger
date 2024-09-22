<?php

namespace Luminar\Components\Messenger\Interfaces;

use Luminar\Components\Messenger\Envelope;

interface MessageHandlerInterface
{
    public function handle(Envelope $envelope): bool;
}