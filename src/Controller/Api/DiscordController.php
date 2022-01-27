<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiscordController extends AbstractController
{
    #[Route('/api/discord', name: 'api_discord')]
    public function index(): Response
    {
        return $this->render('api/discord/index.html.twig', [
            'controller_name' => 'DiscordController',
        ]);
    }
}
