<?php

namespace App\Controller;

use App\Service\Discord as DiscordClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiUserController extends AbstractController
{
    #[Route('/api/user', name: 'api_user')]
    public function index(
        DiscordClient $discord
    ): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $profile = $discord->fetchProfile();
//        $profile['guilds'] = $discord->fetchGuilds();
//        $profile['genesis'] = $discord->fetchGuildMemberInfo(730587900495921214);

        return $this->json($profile);
    }
}
