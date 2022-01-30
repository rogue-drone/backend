<?php

namespace App\Controller\Api;

use App\Repository\GuildRepository;
use App\Service\Discord as DiscordClient;
use App\Service\Restcord;
use Doctrine\Common\Collections\ArrayCollection;
use RestCord\Model\Guild\Guild;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/api/user', name: 'api_user')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(
        Restcord $discord,
        DiscordClient $discordOld,
        GuildRepository $guildRepository
    ): JsonResponse
    {
        $profile = $discord->getProfile();

        return $this->json($profile);
    }
}
