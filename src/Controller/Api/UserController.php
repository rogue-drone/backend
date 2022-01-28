<?php

namespace App\Controller\Api;

use App\Repository\GuildRepository;
use App\Service\Discord as DiscordClient;
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
        DiscordClient $discord,
        GuildRepository $guildRepository
    ): JsonResponse
    {

        /** @var ArrayCollection|Guild[] $guilds */
//        $guilds = $guildRepository->findBy(['user' => $this->getUser()]);
//        dump($guilds);
//        $discordGuilds = array_filter(
//            $discord->fetchGuilds(),
//            /** @var Guild $guild */
//            function ($guild) use ($guilds) {
//                return 1;
//            }
//        );

        $profile = $discord->fetchProfile();
        $profile['guilds'] = $discord->fetchGuilds();
//        $profile['genesis'] = $discord->fetchGuildMemberInfo(730587900495921214);

        return $this->json($profile);
    }
}
