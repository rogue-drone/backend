<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Discord as DiscordClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
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
    #[Route('/auth/connect', name: 'connect_discord_start')]
    public function connectAction(ClientRegistry $registry): RedirectResponse
    {
        /** @var Discord $client */
        $client = $registry->getClient('discord');
        return $client->redirect([
            'email',
            'identify',
            'guilds',
            'guilds.members.read'
        ]);
    }

    #[Route('/auth/connect/check', name: 'connect_discord_check')]
    public function connectCheckAction(
        JWTTokenManagerInterface $manager,
        Request $request,
        TokenStorageInterface $tokenStorage,
        ClientRegistry $registry
    )
    {
        /** @var string $frontendUrl */
        $frontendUrl = $this->getParameter('app.frontend_uri');
        /** @var User $user */
        $user = $this->getUser();
        $response = new RedirectResponse($frontendUrl);
        $response->headers->setCookie(
            Cookie::create('token')
            ->withValue($manager->createFromPayload($user, [
                'discordToken' => $user->getCurrentAccessToken()
            ]))
            ->withExpires((new \DateTime)->setTimestamp($user->getCurrentAccessToken()['expires']))
            ->withSecure(true)
            ->withHttpOnly(false)
            ->withSameSite(Cookie::SAMESITE_LAX)
        );

        return $response;
    }
}
