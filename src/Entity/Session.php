<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $debut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fin = null;

    #[ORM\Column]
    private ?int $messagesEnvoyes = null;

    #[ORM\Column]
    private ?int $messagesRecus = null;

    #[ORM\ManyToOne(inversedBy: 'sessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Operateur $operateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDebut(): ?\DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(\DateTimeInterface $debut): static
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(\DateTimeInterface $fin): static
    {
        $this->fin = $fin;

        return $this;
    }

    public function getMessagesEnvoyes(): ?int
    {
        return $this->messagesEnvoyes;
    }

    public function setMessagesEnvoyes(int $messagesEnvoyes): static
    {
        $this->messagesEnvoyes = $messagesEnvoyes;

        return $this;
    }

    public function getMessagesRecus(): ?int
    {
        return $this->messagesRecus;
    }

    public function setMessagesRecus(int $messagesRecus): static
    {
        $this->messagesRecus = $messagesRecus;

        return $this;
    }

    public function getOperateur(): ?Operateur
    {
        return $this->operateur;
    }

    public function setOperateur(?Operateur $operateur): static
    {
        $this->operateur = $operateur;

        return $this;
    }
}
