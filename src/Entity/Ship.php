<?php

namespace App\Entity;

use App\Repository\ShipRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: ShipRepository::class)]
class Ship
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name;

    #[ORM\ManyToMany(targetEntity: Doctrine::class, mappedBy: 'ships')]
    private Collection $doctrines;

    #[Pure] public function __construct()
    {
        $this->doctrines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<Doctrine>
     */
    public function getDoctrines(): Collection
    {
        return $this->doctrines;
    }

    public function addDoctrine(Doctrine $doctrine): self
    {
        if (!$this->doctrines->contains($doctrine)) {
            $this->doctrines[] = $doctrine;
            $doctrine->addShip($this);
        }

        return $this;
    }

    public function removeDoctrine(Doctrine $doctrine): self
    {
        if ($this->doctrines->removeElement($doctrine)) {
            $doctrine->removeShip($this);
        }

        return $this;
    }
}
