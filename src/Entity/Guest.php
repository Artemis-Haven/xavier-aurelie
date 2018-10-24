<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Guest
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
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="boolean")
     */
    private $attendCeremony;

    /**
     * @ORM\Column(type="boolean")
     */
    private $attendBrunch;

    /**
     * @ORM\Column(type="boolean")
     */
    private $accommodationOnSite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Answer", inversedBy="guests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $answer;

    public function __toString()
    {
        return trim($this->firstname.' '.$this->lastname);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname ?? "";

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname ?? "";

        return $this;
    }

    public function getAttendCeremony(): ?bool
    {
        return $this->attendCeremony;
    }

    public function setAttendCeremony(bool $attendCeremony): self
    {
        $this->attendCeremony = $attendCeremony;

        return $this;
    }

    public function getAttendBrunch(): ?bool
    {
        return $this->attendBrunch;
    }

    public function setAttendBrunch(bool $attendBrunch): self
    {
        $this->attendBrunch = $attendBrunch;

        return $this;
    }

    public function getAccommodationOnSite(): ?bool
    {
        return $this->accommodationOnSite;
    }

    public function setAccommodationOnSite(bool $accommodationOnSite): self
    {
        $this->accommodationOnSite = $accommodationOnSite;

        return $this;
    }

    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }

    public function setAnswer(?Answer $answer): self
    {
        $this->answer = $answer;

        return $this;
    }
}
