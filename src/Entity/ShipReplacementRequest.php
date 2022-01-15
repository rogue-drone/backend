<?php

namespace App\Entity;

use App\Repository\ShipReplacementRequestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShipReplacementRequestRepository::class)]
class ShipReplacementRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'shipReplacementRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private $player;

    #[ORM\ManyToOne(targetEntity: Operation::class, inversedBy: 'shipReplacementRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private $operation;

    #[ORM\Column(type: 'text')]
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?User
    {
        return $this->player;
    }

    public function setPlayer(?User $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getOperation(): ?Operation
    {
        return $this->operation;
    }

    public function setOperation(?Operation $operation): self
    {
        $this->operation = $operation;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
