<?php

namespace App\Controller;

use App\Service\Discord as DiscordClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Wohali\OAuth2\Client\Provider\Discord;

class AuthController extends AbstractController
{
    #[Route('/auth', name: 'auth')]
    public function index(DiscordClient $discord): \Symfony\Component\HttpFoundation\JsonResponse
    {
        if ($this->getUser()) {
            $profile = $discord->fetchProfile();
            $profile['guilds'] = $discord->fetchGuilds();
            $profile['genesis'] = $discord->fetchGuildMemberInfo(730587900495921214);
            $profile['genesis_debug'] = $discord->fetchGuildInfo(730587900495921214);
            return $this->json($profile);
        }

        return $this->createAccessDeniedException();
    }

    #[Route('/auth/connect', name: 'connect_discord_start')]
    public function connectAction(ClientRegistry $registry): RedirectResponse
    {
        /** @var Discord $client */
        $client = $registry->getClient('discord');
        return $client->redirect([
            'bot',
            'identify',
            'guilds',
            'guilds.members.read'
        ]);
    }

    #[Route('/auth/connect/check', name: 'connect_discord_check')]
    public function connectCheckAction(
        Request $request,
        TokenStorageInterface $tokenStorage,
        JWTTokenManagerInterface $manager,
        ClientRegistry $registry
    )
    {
        $frontendUrl = $this->getParameter('app.frontend_uri');

        $token = $manager->createFromPayload($this->getUser(), [
            'discordToken' => $this->getUser()->getCurrentAccessToken()
        ]);

        return new RedirectResponse($frontendUrl . '/?token=' . $token);
    }
}
