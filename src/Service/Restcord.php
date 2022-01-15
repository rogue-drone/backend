<?php

namespace App\Service;

use RestCord\DiscordClient;
use RestCord\Model\Guild\Guild;
use RestCord\Model\Guild\GuildMember;

class Restcord
{
    private DiscordClient $client;

    public function __construct(DiscordClient $client)
    {
        $this->client = $client;
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
