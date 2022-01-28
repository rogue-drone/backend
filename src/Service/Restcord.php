<?php

namespace App\Service;

use App\Entity\User;
use JsonException;
use RestCord\DiscordClient;
use RestCord\Model\Guild\Guild;
use RestCord\Model\Guild\GuildMember;
use Symfony\Component\Security\Core\User\UserInterface;

class Restcord
{
    public function __construct(
        private DiscordClient $client
    ) {}

    /**
     * @param UserInterface $user
     * @return Guild[]
     * @throws JsonException
     */
    public function getUserGuilds(UserInterface $user): array
    {
        return (new DiscordClient(['token' => $user->getCurrentAccessToken()['access_token']]))
            ->user
            ->getCurrentUserGuilds([]);
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
