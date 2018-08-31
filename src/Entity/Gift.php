<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GiftRepository")
 */
class Gift
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
    private $giver;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(0)
     * @Assert\Regex(pattern="/^\d+$/", message="Le montant ne doit pas contenir de caractères spéciaux")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

    /**
     * @ORM\Column(type="boolean")
     */
    private $onlinePayment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ListItem", inversedBy="gifts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $listItem;


    public function __construct()
    {
        $this->onlinePayment = true;
        $this->onlinePayment = false;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGiver(): ?string
    {
        return $this->giver;
    }

    public function setGiver(string $giver): self
    {
        $this->giver = $giver;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount($amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getOnlinePayment(): ?bool
    {
        return $this->onlinePayment;
    }

    public function setOnlinePayment(bool $onlinePayment): self
    {
        $this->onlinePayment = $onlinePayment;

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

    public function getListItem(): ?ListItem
    {
        return $this->listItem;
    }

    public function setListItem(?ListItem $listItem): self
    {
        $this->listItem = $listItem;

        return $this;
    }
}
