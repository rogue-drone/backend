<?php

namespace App\Controller;

use App\Entity\Guild;
use App\Entity\User;
use App\Repository\GuildRepository;
use App\Service\Restcord;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ApiGuildController extends AbstractController
{
    #[Route('/api/guild', name: 'api_guild')]
    public function index(GuildRepository $repository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->json($user->getGuilds(), context: ['groups' => 'list']);
    }

    #[Route('/api/guild/{discordId}', name: 'api_guild_show')]
    public function show(Guild $guild, Restcord $restcord)
    {
        dump($restcord->getGuild($guild->getDiscordId()));
        return $this->json($guild, context: ['groups' => 'list']);
    }
}
