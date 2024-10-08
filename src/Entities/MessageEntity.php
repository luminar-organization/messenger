<?php

namespace Luminar\Components\Messenger\Entities;

use DateTime;
use Luminar\Database\ORM\Column;
use Luminar\Database\ORM\Entity;
use Luminar\Database\ORM\TypesAttributes;

#[Entity(name: "messenger_messages")]
class MessageEntity
{
    #[Column(name: "id")]
    protected int $id;

    #[Column(name: "content", type: TypesAttributes::TYPE_LONGTEXT)]
    protected string $content;

    #[Column(name: "createdAt")]
    protected DateTime $createdAt;

    #[Column(name: "availableAt")]
    protected DateTime $availableAt;

    #[Column(name: "deliveredAt")]
    protected ?DateTime $deliveredAt = null;

    #[Column(name: "status")]
    protected int $status;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getAvailableAt(): DateTime
    {
        return $this->availableAt;
    }

    /**
     * @param DateTime $availableAt
     */
    public function setAvailableAt(DateTime $availableAt): void
    {
        $this->availableAt = $availableAt;
    }

    /**
     * @return ?DateTime
     */
    public function getDeliveredAt(): ?DateTime
    {
        return $this->deliveredAt ?? null;
    }

    /**
     * @param DateTime $deliveredAt
     */
    public function setDeliveredAt(DateTime $deliveredAt): void
    {
        $this->deliveredAt = $deliveredAt;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }
}