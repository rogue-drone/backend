<?php

namespace App\Service;

use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Discord
{
    private HttpClientInterface $httpClient;
    private Security $security;

    public function __construct(HttpClientInterface $discord, Security $security)
    {
        $this->httpClient = $discord;
        $this->security = $security;
    }

    public function fetchProfile()
    {
        return $this->httpClient->request(
            'GET',
            '/api/v9/users/@me',
            [
                'auth_bearer' => $this->security->getUser()->getCurrentAccessToken()['access_token']
            ]
        )->toArray();
    }

    public function fetchGuilds()
    {
        return $this->httpClient->request(
            'GET',
            '/api/v9/users/@me/guilds',
            [
                'auth_bearer' => $this->security->getUser()->getCurrentAccessToken()['access_token']
            ]
        )->toArray();
    }

    public function fetchGuildMemberInfo($guildId)
    {
        return $this->httpClient->request(
            'GET',
            '/api/v9/users/@me/guilds/'.$guildId.'/member',
            [
                'auth_bearer' => $this->security->getUser()->getCurrentAccessToken()['access_token']
            ]
        )->toArray();
    }

    public function fetchGuildInfo($guildId)
    {
        return $this->httpClient->request(
            'GET',
            '/api/v9/guilds/'.$guildId.'/roles',
            [
                'auth_bearer' => $this->security->getUser()->getCurrentAccessToken()['access_token']
            ]
        )->toArray();
    }
}
