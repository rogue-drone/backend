<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $username;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    private $discordId;

    #[ORM\Column(type: 'text', nullable: true)]
    private $currentAccessToken;

    #[ORM\ManyToMany(targetEntity: Guild::class, mappedBy: 'administrators')]
    private $guilds;

    #[ORM\OneToMany(mappedBy: 'fleetCommander', targetEntity: Operation::class)]
    private $operations;

    #[ORM\OneToMany(mappedBy: 'requster', targetEntity: ShipReplacementRequest::class)]
    private $shipReplacementRequests;

    public function __construct()
    {
        $this->guilds = new ArrayCollection();
        $this->operations = new ArrayCollection();
        $this->shipReplacementRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getDiscordId(): ?string
    {
        return $this->discordId;
    }

    public function setDiscordId(string $discordId): self
    {
        $this->discordId = $discordId;

        return $this;
    }

    public function getCurrentAccessToken(): ?array
    {
        if ($this->currentAccessToken !== null) {
            return json_decode($this->currentAccessToken,true);
        }

        return null;
    }

    public function setCurrentAccessToken(?array $currentAccessToken): self
    {
        $this->currentAccessToken = json_encode($currentAccessToken);

        return $this;
    }

    /**
     * @return Collection|Guild[]
     */
    public function getGuilds(): Collection
    {
        return $this->guilds;
    }

    public function addGuild(Guild $guild): self
    {
        if (!$this->guilds->contains($guild)) {
            $this->guilds[] = $guild;
            $guild->addAdministrator($this);
        }

        return $this;
    }

    public function removeGuild(Guild $guild): self
    {
        if ($this->guilds->removeElement($guild)) {
            $guild->removeAdministrator($this);
        }

        return $this;
    }

    /**
     * @return Collection|Operation[]
     */
    public function getOperations(): Collection
    {
        return $this->operations;
    }

    public function addOperation(Operation $operation): self
    {
        if (!$this->operations->contains($operation)) {
            $this->operations[] = $operation;
            $operation->setFleetCommander($this);
        }

        return $this;
    }

    public function removeOperation(Operation $operation): self
    {
        if ($this->operations->removeElement($operation)) {
            // set the owning side to null (unless already changed)
            if ($operation->getFleetCommander() === $this) {
                $operation->setFleetCommander(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ShipReplacementRequest[]
     */
    public function getShipReplacementRequests(): Collection
    {
        return $this->shipReplacementRequests;
    }

    public function addShipReplacementRequest(ShipReplacementRequest $shipReplacementRequest): self
    {
        if (!$this->shipReplacementRequests->contains($shipReplacementRequest)) {
            $this->shipReplacementRequests[] = $shipReplacementRequest;
            $shipReplacementRequest->setPlayer($this);
        }

        return $this;
    }

    public function removeShipReplacementRequest(ShipReplacementRequest $shipReplacementRequest): self
    {
        if ($this->shipReplacementRequests->removeElement($shipReplacementRequest)) {
            // set the owning side to null (unless already changed)
            if ($shipReplacementRequest->getPlayer() === $this) {
                $shipReplacementRequest->setPlayer(null);
            }
        }

        return $this;
    }
}
