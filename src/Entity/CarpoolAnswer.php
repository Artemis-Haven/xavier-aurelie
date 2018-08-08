<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CarpoolAnswerRepository")
 */
class CarpoolAnswer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbrOfSeatsRequested;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $details;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CarpoolSearch", inversedBy="answers")
     * @ORM\JoinColumn()
     */
    private $search;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CarpoolProposal", inversedBy="answers")
     * @ORM\JoinColumn()
     */
    private $proposal;

    const STATUS_PENDING = "En attente";
    const STATUS_ACCEPTED = "Acceptée";
    const STATUS_REJECTED = "Refusée";


    public function __construct()
    {
        $this->status = self::STATUS_PENDING;
    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;


    public function getId()
    {
        return $this->id;
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

    public function getNbrOfSeatsRequested(): ?int
    {
        return $this->nbrOfSeatsRequested;
    }

    public function setNbrOfSeatsRequested(int $nbrOfSeatsRequested): self
    {
        $this->nbrOfSeatsRequested = $nbrOfSeatsRequested;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): self
    {
        $this->details = $details;

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

    public function getSearch(): ?CarpoolSearch
    {
        return $this->search;
    }

    public function setSearch(?CarpoolSearch $search): self
    {
        $this->search = $search;

        return $this;
    }

    public function getProposal(): ?CarpoolProposal
    {
        return $this->proposal;
    }

    public function setProposal(?CarpoolProposal $proposal): self
    {
        $this->proposal = $proposal;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        if (in_array($status, [self::STATUS_PENDING, self::STATUS_ACCEPTED, self::STATUS_REJECTED])) {
            $this->status = $status;
        }
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
}
