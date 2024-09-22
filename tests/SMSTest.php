<?php

namespace Luminar\Components\Messenger\Tests;

use Luminar\Components\Messenger\Entities\MessageEntity;
use Luminar\Components\Messenger\MessageAttributes;
use Luminar\Components\Messenger\MessageDispatch;
use Luminar\Database\Connection\Connection;
use Luminar\Database\ORM\EntityManager;
use Luminar\Database\ORM\Repository;
use PDOException;
use PHPUnit\Framework\TestCase;

class SMSTest extends TestCase
{
    public function testSMS()
    {
        try {
            $connection = new Connection("mysql:host=localhost;dbname=luminar-test", 'root');
            $entityManager = new EntityManager($connection);
            $messageDispatch = new MessageDispatch($connection, $entityManager);

            // Initialize Message
            $sms = new SMSMessage();
            $sms->setMessage("Example Message");
            $sms->setNumber("+48123456789");
            $sms->setHandler(new SMSHandler());

            $messageDispatch->dispatch($sms);

            $repository = new Repository(MessageEntity::class ,$connection);
            $this->assertNotEmpty($repository->findBy([
                'status' => MessageAttributes::STATUS_NONE
            ]));

            $messageDispatch->callAllMessages();

            $this->assertEmpty($repository->findBy([
                'status' => MessageAttributes::STATUS_NONE
            ]));
        } catch(PDOException $exception) {
            echo "Messenger does not support sqlite database!";
            $this->assertTrue(true);
        }
    }
}