<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpotRepository")
 */
class Spot
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Role")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="spots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

//    /**
//     * @ORM\Column(type="string", length=255)
//     */
//    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isAvailable(): bool
    {
        return $this->user === null;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function attemptToReserve(User $user): bool
    {
        if ($this->event->hasUser($user)) {
            return false;
        }

        $this->setUser($user);

        return true;
    }

    public function __toString(): string
    {
        return $this->role->getName();
    }
}
