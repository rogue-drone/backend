<?php

namespace App\Entity;

use App\Repository\OperationRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: OperationRepository::class)]
class Operation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name;

    #[ORM\Column(type: 'datetimetz')]
    private ?DateTimeInterface $date;

    #[ORM\Column(type: 'text')]
    private ?string $description;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'operations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $fleetCommander;

    #[ORM\OneToMany(mappedBy: 'operation', targetEntity: ShipReplacementRequest::class)]
    private Collection $shipReplacementRequests;

    #[Pure] public function __construct()
    {
        $this->shipReplacementRequests = new ArrayCollection();
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

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getFleetCommander(): ?User
    {
        return $this->fleetCommander;
    }

    public function setFleetCommander(?User $fleetCommander): self
    {
        $this->fleetCommander = $fleetCommander;

        return $this;
    }

    /**
     * @return Collection<ShipReplacementRequest>
     */
    public function getShipReplacementRequests(): Collection
    {
        return $this->shipReplacementRequests;
    }

    public function addShipReplacementRequest(ShipReplacementRequest $shipReplacementRequest): self
    {
        if (!$this->shipReplacementRequests->contains($shipReplacementRequest)) {
            $this->shipReplacementRequests[] = $shipReplacementRequest;
            $shipReplacementRequest->setOperation($this);
        }

        return $this;
    }

    public function removeShipReplacementRequest(ShipReplacementRequest $shipReplacementRequest): self
    {
        // set the owning side to null (unless already changed)
        if (
            $this->shipReplacementRequests->removeElement($shipReplacementRequest)
            && $shipReplacementRequest->getOperation() === $this
        ) {
            $shipReplacementRequest->setOperation(null);
        }

        return $this;
    }
}
