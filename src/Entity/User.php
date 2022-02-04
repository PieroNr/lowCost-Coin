<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @UniqueEntity(fields={"email"}, message="Un utilisateur existe déjà avec cette adresse email.")
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $firstname;

    #[ORM\Column(type: 'string', length: 100)]
    private $lastname;

    #[ORM\Column(type: 'string', length: 100)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = ['ROLE_USER'];

    #[ORM\Column(type: 'string', length: 100)]
    private $password;

    #[ORM\OneToMany(mappedBy: 'sellerId', targetEntity: Product::class, orphanRemoval: true, fetch: 'EAGER')]
    private $products;

    #[ORM\OneToMany(mappedBy: 'userSender', targetEntity: Note::class, fetch: 'EAGER')]
    private $givenNotes;

    #[ORM\OneToMany(mappedBy: 'userReceiver', targetEntity: Note::class, fetch: 'EAGER')]
    private $receivedNotes;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->givenNotes = new ArrayCollection();
        $this->receivedNotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }



    public function setRoles(Array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRoles(string $roles): self
    {
        if (!in_array($roles, $this->roles)) {
            $this->roles[] = $roles;
        }

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored in a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * Returns the identifier for this user (e.g. its username or email address).
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setSellerId($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getSellerId() === $this) {
                $product->setSellerId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getGivenNotes(): Collection
    {
        return $this->givenNotes;
    }

    public function addGivenNote(Note $givenNote): self
    {
        if (!$this->givenNotes->contains($givenNote)) {
            $this->givenNotes[] = $givenNote;
            $givenNote->setUserSender($this);
        }

        return $this;
    }

    public function sendUpNote(User $userReceiver): Note
    {
        $note = new Note();
        $note->setUserSender($this)
            ->setUserReceiver($userReceiver)
            ->setNote(1);

        return $note;
    }

    public function sendDownNote(User $userReceiver): Note
    {
        $note = new Note();
        $note->setUserSender($this)
            ->setUserReceiver($userReceiver)
            ->setNote(0);

        return $note;
    }

    public function getTotalNote(): ?int
    {
        $totalNote = 0;
        $arrayNotes = $this->getReceivedNotes();
        foreach ($arrayNotes as $note){
            if ($note->getNote() == 1){
                $totalNote++;
            } else {
                $totalNote--;
            }
        }

        return $totalNote;
    }

    public function removeGivenNote(Note $givenNote): self
    {
        if ($this->givenNotes->removeElement($givenNote)) {
            // set the owning side to null (unless already changed)
            if ($givenNote->getUserSender() === $this) {
                $givenNote->setUserSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getReceivedNotes(): Collection
    {
        return $this->receivedNotes;
    }

    public function addReceivedNote(Note $receivedNote): self
    {
        if (!$this->receivedNotes->contains($receivedNote)) {
            $this->receivedNotes[] = $receivedNote;
            $receivedNote->setUserReceiver($this);
        }

        return $this;
    }

    public function removeReceivedNote(Note $receivedNote): self
    {
        if ($this->receivedNotes->removeElement($receivedNote)) {
            // set the owning side to null (unless already changed)
            if ($receivedNote->getUserReceiver() === $this) {
                $receivedNote->setUserReceiver(null);
            }
        }

        return $this;
    }



}
