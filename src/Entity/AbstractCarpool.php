<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

abstract class AbstractCarpool
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $direction;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $location;

    /**
     * @ORM\Column(type="text")
     */
    protected $details;

    /**
     * @ORM\Column(type="integer")
     */
    protected $nbrOfSeats;

    /**
     * @ORM\Column(type="integer")
     */
    protected $reservedSeats;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $author;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $uuid;

    protected $answers;


    public function __construct()
    {
        $this->reservedSeats = 0;
        $this->answers = new ArrayCollection();
        $this->uuid = uniqid();
    }


    public function getId()
    {
        return $this->id;
    }

    public function getDirection(): ?string
    {
        return $this->direction;
    }

    public function setDirection(string $direction): self
    {
        $this->direction = $direction;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getNbrOfSeats(): ?int
    {
        return $this->nbrOfSeats;
    }

    public function setNbrOfSeats(int $nbrOfSeats): self
    {
        $this->nbrOfSeats = $nbrOfSeats;

        return $this;
    }

    public function getReservedSeats(): ?int
    {
        return $this->reservedSeats;
    }

    public function setReservedSeats(int $reservedSeats): self
    {
        $this->reservedSeats = $reservedSeats;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection|CarpoolAnswer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(CarpoolAnswer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setCarpoolProposal($this);
        }

        return $this;
    }

    public function removeAnswer(CarpoolAnswer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            // set the owning side to null (unless already changed)
            if ($answer->getCarpoolProposal() === $this) {
                $answer->setCarpoolProposal(null);
            }
        }

        return $this;
    }

    public function isCompleted(): bool
    {
        $seats = $this->getNbrOfSeats();
        foreach ($this->getAnswers() as $answer) {
            if ($answer->getStatus() == CarpoolAnswer::STATUS_ACCEPTED) {
                $seats -= $answer->getNbrOfSeatsRequested();
            }
        }
        return $seats == 0;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }
}
