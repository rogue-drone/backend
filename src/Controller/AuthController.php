<?php

namespace App\Controller;

use App\Entity\Guild;
use App\Entity\User;
use App\Repository\GuildRepository;
use App\Repository\UserRepository;
use App\Service\Restcord;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\DiscordClient;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Wohali\OAuth2\Client\Provider\DiscordResourceOwner;
use function Sentry\captureException;

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

    /**
     * @throws JsonException
     */
    #[Route('/auth/connect/check', name: 'connect_discord_check')]
    public function connectCheck(
        JWTTokenManagerInterface $manager
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

    #[Route('/bot/connect/{id}', name: 'connect_bot_start')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function connectBot(ClientRegistry $registry, string $id): RedirectResponse
    {
        $options = [
            'disable_guild_select' => 'true',
            'guild_id' => (int)$id,
            'permissions' => 19520,
            'redirect_uri' => $this->generateUrl('connect_bot_check', referenceType: UrlGeneratorInterface::ABSOLUTE_URL)
        ];

        /** @var DiscordClient $client */
        $client = $registry->getClient('discord');

        return $client->redirect(
            [
                'bot',
                'applications.commands',
                'guilds',
                'guilds.members.read'
            ],
            $options
        );
    }

    #[Route('/bot/connect/check', name: 'connect_bot_check', priority: 2)]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function connectBotCheck(
        Request $request,
        ClientRegistry $registry,
        Restcord $restcord,
        GuildRepository $guildRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ): RedirectResponse
    {
        /** @var string $frontendUrl */
        $frontendUrl = $this->getParameter('app.frontend_uri');

        $guildDiscordId = $request->query->get('guild_id');

        try {
            $discordGuild = $restcord->getGuild($guildDiscordId);


            $guild = $guildRepository->findOneBy([
                'discordId' => $guildDiscordId
            ]);

            $user = $this->getUser();

            if (!$guild) {
                $guild = new Guild();
                $guild->setDiscordId($guildDiscordId);
                $guild->addAdministrator($user);
                $guild->addUser($user);
                $guild->setName($discordGuild->name);
                $guild->setIcon($discordGuild->getIcon());
                $entityManager->persist($guild);
            } else {
                if ($guild->getName() !== $discordGuild->name) {
                    $guild->setName($discordGuild->name);
                }

                if ($guild->getIcon() !== $discordGuild->getIcon()) {
                    $guild->setIcon($discordGuild->getIcon());
                }
            }

            $entityManager->flush();

            return new RedirectResponse($frontendUrl . 'guild/' . $guildDiscordId);
        } catch (\Exception $e) {
            captureException($e);
            return new RedirectResponse($frontendUrl . 'error');
        }
    }
}
