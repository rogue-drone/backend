<?php

namespace App\Controller\Api;

use App\Entity\Guild;
use App\Entity\User;
use App\Repository\GuildRepository;
use App\Service\Discord;
use App\Service\Restcord;
use JsonException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class GuildController extends AbstractController
{
    #[Route('/api/guild', name: 'api_guild', methods: ['GET'])]
    public function index(GuildRepository $repository): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->json($user->getManagedGuilds(), context: ['groups' => 'list']);
    }

    /**
     * @param Restcord $discord
     * @return JsonResponse
     */
    #[Route('/api/guild/connectable', name: 'api_guild_connectable', methods: ['GET'])]
    public function connectable(Restcord $discord): JsonResponse
    {
        $guilds = $discord->getUserGuilds();

        $guilds = array_filter($guilds, static fn($guild) => ($guild->permissions & (1 << 5)) == true);

        return $this->json($guilds, context: ['groups' => 'list']);
    }

    #[Route('/api/guild/{discordId}', name: 'api_guild_show', methods: ['GET'])]
    public function show(Guild $guild): JsonResponse
    {
        return $this->json($guild, context: ['groups' => 'show']);
    }

    #[Route('/api/guild/{discordId}/acl', name: 'api_guild_show', methods: ['GET'])]
    public function acl(Guild $guild): JsonResponse
    {
        return $this->json($guild, context: ['groups' => 'list']);
    }
}
