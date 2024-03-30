<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SmsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SmsRepository::class)]
#[ApiResource]
class Sms
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $sender = null;

    #[ORM\Column(length: 180)]
    private ?string $recipient = null;

    #[ORM\Column(length: 255)]
    private ?string $contentOfMessage = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $add_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?string
    {
        return $this->sender;
    }

    public function setSender(string $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getRecipient(): ?string
    {
        return $this->recipient;
    }

    public function setRecipient(string $recipient): static
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getContentOfMessage(): ?string
    {
        return $this->contentOfMessage;
    }

    public function setContentOfMessage(string $contentOfMessage): static
    {
        $this->contentOfMessage = $contentOfMessage;

        return $this;
    }

    public function getAddDate(): ?\DateTimeInterface
    {
        return $this->add_date;
    }

    public function setAddDate(\DateTimeInterface $add_date): static
    {
        $this->add_date = $add_date;

        return $this;
    }
}
