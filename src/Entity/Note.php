<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'boolean')]
    private $note;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'givenNotes')]
    #[ORM\JoinColumn(nullable: false)]
    private $userSender;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'receivedNotes')]
    #[ORM\JoinColumn(nullable: false)]
    private $userReceiver;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?bool
    {
        return $this->note;
    }

    public function setNote(bool $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getUserSender(): ?User
    {
        return $this->userSender;
    }

    public function setUserSender(?User $userSender): self
    {
        $this->userSender = $userSender;

        return $this;
    }

    public function getUserReceiver(): ?User
    {
        return $this->userReceiver;
    }

    public function setUserReceiver(?User $userReceiver): self
    {
        $this->userReceiver = $userReceiver;

        return $this;
    }
}
