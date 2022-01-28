<?php

namespace App\Entity;

use App\Repository\GuildRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GuildRepository::class)]
class Guild
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['list', 'show'])]
    private ?string $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['list', 'show'])]
    private ?string $discordId;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['list', 'show'])]
    private ?string $icon;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'managedGuilds')]
    #[ORM\JoinTable(name: 'guilds_administrators')]
    private Collection $administrators;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'guilds')]
    #[ORM\JoinTable(name: 'guilds_users')]
    private Collection $users;

    #[Pure] public function __construct()
    {
        $this->administrators = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function getDiscordId(): ?string
    {
        return $this->discordId;
    }

    public function setDiscordId(string $discordId): self
    {
        $this->discordId = $discordId;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return Collection<User>
     */
    public function getAdministrators(): Collection
    {
        return $this->administrators;
    }

    public function addAdministrator(User $administrator): self
    {
        if (!$this->administrators->contains($administrator)) {
            $this->administrators[] = $administrator;
            $administrator->addGuild($this);
        }

        return $this;
    }

    public function removeAdministrator(User $administrator): self
    {
        $this->administrators->removeElement($administrator);

        return $this;
    }

    /**
     * @return Collection<User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }
}
