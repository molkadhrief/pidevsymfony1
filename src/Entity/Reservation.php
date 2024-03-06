<?php
// src/Entity/Reservation.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $nombreDeParticipants;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNombreDeParticipants(): ?int
    {
        return $this->nombreDeParticipants;
    }

    public function setNombreDeParticipants(int $nombreDeParticipants): self
    {
        $this->nombreDeParticipants = $nombreDeParticipants;

        return $this;
    }
}