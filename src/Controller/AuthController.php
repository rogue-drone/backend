<?php

namespace App\Controller;

use App\Entity\Guild;
use App\Entity\User;
use App\Repository\GuildRepository;
use App\Repository\UserRepository;
use App\Service\Restcord;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\DiscordClient;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Wohali\OAuth2\Client\Provider\DiscordResourceOwner;

class AuthController extends AbstractController
{
    #[Route('/auth/connect', name: 'connect_discord_start')]
    public function connect(ClientRegistry $registry): RedirectResponse
    {
        /** @var DiscordClient $client */
        $client = $registry->getClient('discord');
        return $client->redirect([
            'email',
            'identify',
            'guilds',
            'guilds.members.read'
        ]);
    }

    #[Route('/auth/connect/check', name: 'connect_discord_check')]
    public function connectCheck(
        JWTTokenManagerInterface $manager,
        Request $request,
        TokenStorageInterface $tokenStorage,
        ClientRegistry $registry
    ): RedirectResponse
    {
        /** @var string $frontendUrl */
        $frontendUrl = $this->getParameter('app.frontend_uri');
        /** @var User $user */
        $user = $this->getUser();
        $response = new RedirectResponse($frontendUrl);
        $response->headers->setCookie(
            Cookie::create('token')
            ->withValue($manager->createFromPayload($user, [
                'discordToken' => $user->getCurrentAccessToken()['access_token']
            ]))
            ->withExpires((new \DateTime)->setTimestamp($user->getCurrentAccessToken()['expires']))
            ->withSecure(true)
            ->withHttpOnly(false)
            ->withSameSite(Cookie::SAMESITE_LAX)
        );

        return $response;
    }

    #[Route('/bot/connect', name: 'connect_bot_start')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function connectBot(ClientRegistry $registry): RedirectResponse
    {
        /** @var DiscordClient $client */
        $client = $registry->getClient('bot');
        return $client->redirect(
            [
                'bot',
                'applications.commands',
                'guilds',
                'guilds.members.read'
            ]
        );
    }

    #[Route('/bot/connect/check', name: 'connect_bot_check')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function connectBotCheck(
        Request $request,
        ClientRegistry $registry,
        Restcord $restcord,
        GuildRepository $guildRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    )
    {
        /** @var string $frontendUrl */
        $frontendUrl = $this->getParameter('app.frontend_uri');

        /** @var DiscordClient $client */
        $client = $registry->getClient('bot');

        $guildDiscordId = $request->query->get('guild_id');

        try {
            // the exact class depends on which provider you're using
            /** @var DiscordResourceOwner $user */
            $user = $client->fetchUser();
            $discordGuild = $restcord->getGuild($guildDiscordId);


            $guild = $guildRepository->findOneBy([
                'discordId' => $guildDiscordId
            ]);

            if (!$guild) {
                $guild = new Guild();
                $guild->setDiscordId($guildDiscordId);
                $guild->addAdministrator($userRepository->findOneBy(['discordId' => $user->getId()]));
                $guild->setName($discordGuild->name);
                $guild->setIcon($discordGuild->icon ?? null);
                $entityManager->persist($guild);
            }

            if ($guild->getName() != $discordGuild->name) {
                $guild->setName($discordGuild->name);
            }

            if ($guild->getIcon() != $discordGuild->icon) {
                $guild->setIcon($discordGuild->icon);
            }

            $entityManager->flush();

            return new RedirectResponse($frontendUrl . '/guild/' . $guildDiscordId);
        } catch (IdentityProviderException $e) {
            return new RedirectResponse($frontendUrl . '/error');
        }
    }
}
