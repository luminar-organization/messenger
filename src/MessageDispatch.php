<?php

namespace Luminar\Components\Messenger;

use DateTime;
use Exception;
use Luminar\Components\Messenger\Entities\MessageEntity;
use Luminar\Components\Messenger\Interfaces\MessageHandlerInterface;
use Luminar\Components\Messenger\Interfaces\MessageInterface;
use Luminar\Database\Connection\Connection;
use Luminar\Database\ORM\EntityManager;
use Luminar\Database\ORM\Repository;
use PDO;
use PDOException;
use ReflectionException;

class MessageDispatch
{
    /**
     * @var Connection $connection
     */
    protected Connection $connection;

    /**
     * @var EntityManager $entityManager
     */
    protected EntityManager $entityManager;

    /**
     * @param Connection $connection
     * @param EntityManager $entityManager
     * @throws Exception
     */
    public function __construct(Connection $connection, EntityManager $entityManager)
    {
        $this->connection = $connection;
        $this->entityManager = $entityManager;


        // Check messenger table exists
        try {
            $result = $connection->query("SELECT 1 FROM messenger_messages LIMIT 1");
        } catch (PDOException $e) {
            $schema = $entityManager->schema(new MessageEntity());
            $this->connection->query($schema['query']);
        }
    }

    /**
     * @param MessageInterface $message
     * @return void
     * @throws Exception
     */
    public function dispatch(MessageInterface $message): void
    {
        $messageEntity = new MessageEntity();
        $messageEntity->setContent([base64_encode($message->getContent())]);
        $messageEntity->setAvailableAt($message->getAvailable());
        $messageEntity->setCreatedAt(new DateTime());
        $messageEntity->setStatus(MessageAttributes::STATUS_NONE);
        $this->entityManager->persist($messageEntity);
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws Exception
     */
    public function callAllMessages(): void
    {
        $repository = new Repository(MessageEntity::class, $this->connection);
        $messages = $repository->getAll();
        foreach($messages as $message) {
            $this->callMessage($message, $this->entityManager);
        }
    }

    /**
     * @param MessageEntity $messageEntity
     * @param EntityManager $entityManager
     * @return void
     * @throws Exception
     */
    public function callMessage(MessageEntity $messageEntity, EntityManager $entityManager): void
    {
        if($messageEntity->getStatus() === MessageAttributes::STATUS_DELIVERED or $messageEntity->getStatus() === MessageAttributes::STATUS_FAILED or $messageEntity->getStatus() === MessageAttributes::STATUS_IN_DELIVERY) return;
        $content = base64_decode($messageEntity->getContent()[0]);
        $content = Envelope::unSerialize($content);
        $currentDate = new DateTime();
        if($messageEntity->getAvailableAt() > $currentDate) {
            if($messageEntity->getStatus() === MessageAttributes::STATUS_NONE) {
                $messageEntity->setStatus(MessageAttributes::STATUS_IN_QUEUE);
                $entityManager->persist($messageEntity);
                return;
            }
            return;
        }

        // Message can be delivered
        $messageEntity->setStatus(MessageAttributes::STATUS_IN_DELIVERY);
        $entityManager->persist($messageEntity);

        // Delivering message
        $handler = $content->getStamps()['handler'];
        if(!($handler instanceof MessageHandlerInterface)) {
            $messageEntity->setStatus(MessageAttributes::STATUS_FAILED);
            $entityManager->persist($messageEntity);
            return;
        }

        $status = $handler->handle($content);
        if(!$status) {
            $messageEntity->setStatus(MessageAttributes::STATUS_FAILED);
            $entityManager->persist($messageEntity);
            return;
        }

        $messageEntity->setStatus(MessageAttributes::STATUS_DELIVERED);
        $messageEntity->setDeliveredAt(new DateTime());
        $entityManager->persist($messageEntity);
    }
}