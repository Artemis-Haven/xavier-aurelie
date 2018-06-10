<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
    protected $nbrOfPersons;

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

    public function getNbrOfPersons(): ?int
    {
        return $this->nbrOfPersons;
    }

    public function setNbrOfPersons(int $nbrOfPersons): self
    {
        $this->nbrOfPersons = $nbrOfPersons;

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
}
