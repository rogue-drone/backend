<?php

namespace App\Service;

use App\Entity\User;
use JsonException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Discord
{
    public function __construct(
        private HttpClientInterface $discord,
        private Security $security
    ) {}

    private function getUser(): User|UserInterface
    {
        return $this->security->getUser();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws JsonException
     */
    public function fetchProfile(): array
    {
        return $this->discord->request(
            'GET',
            '/api/v9/users/@me',
            [
                'auth_bearer' => $this->getUser()->getCurrentAccessToken()['access_token']
            ]
        )->toArray();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws JsonException
     */
    public function fetchGuilds(): array
    {
        return $this->discord->request(
            'GET',
            '/api/v9/users/@me/guilds',
            [
                'auth_bearer' => $this->getUser()->getCurrentAccessToken()['access_token']
            ]
        )->toArray();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws JsonException
     */
    public function fetchGuildMemberInfo($guildId): array
    {
        return $this->discord->request(
            'GET',
            '/api/v9/users/@me/guilds/'.$guildId.'/member',
            [
                'auth_bearer' => $this->getUser()->getCurrentAccessToken()['access_token']
            ]
        )->toArray();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws JsonException
     */
    public function fetchGuildInfo($guildId): array
    {
        return $this->discord->request(
            'GET',
            '/api/v9/guilds/'.$guildId,
            [
                'auth_bearer' => $this->getUser()->getCurrentAccessToken()['access_token']
            ]
        )->toArray();
    }
}
