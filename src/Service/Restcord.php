<?php

namespace App\Service;

use App\Entity\User;
use JsonException;
use RestCord\DiscordClient;
use RestCord\Model\Guild\Guild;
use RestCord\Model\Guild\GuildMember;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class Restcord
{
    public function __construct(
        private DiscordClient $client,
        private Security $security,
        private ?DiscordClient $userClient
    ) {
        if ($this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->userClient = new DiscordClient([
                'token' => $this->security->getUser()->getCurrentAccessToken()['access_token'],
                'tokenType' => 'OAuth'
            ]);
        }
    }

    public function getProfile()
    {
        return $this->userClient->user->getCurrentUser([]);
    }

    /**
     * @return Guild[]
     */
    public function getUserGuilds(): array
    {
        return $this->userClient
            ->user->getCurrentUserGuilds([]);
    }

    public function getGuild($guildId): Guild
    {
        return $this->client->guild->getGuild([
            'guild.id' => (int)$guildId
        ]);
    }

    public function getGuildMember($guildId, $userId): GuildMember
    {
        return $this->client->guild->getGuildMember([
            'guild.id' => (int)$guildId,
            'user.id'  => (int)$userId
        ]);
    }
}
