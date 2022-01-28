<?php

namespace App\Controller\Api;

use App\Entity\Guild;
use App\Entity\User;
use App\Repository\GuildRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class GuildController extends AbstractController
{
    #[Route('/api/guild', name: 'api_guild')]
    public function index(GuildRepository $repository): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->json($user->getManagedGuilds(), context: ['groups' => 'list']);
    }

    #[Route('/api/guild/{discordId}', name: 'api_guild_show')]
    public function show(Guild $guild): JsonResponse
    {
        return $this->json($guild, context: ['groups' => 'list']);
    }

    #[Route('/api/guild/{discordId}/acl', name: 'api_guild_show')]
    public function acl(Guild $guild): JsonResponse
    {
        return $this->json($guild, context: ['groups' => 'list']);
    }
}
