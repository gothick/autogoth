<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\User;
use App\RemoteService\RemoteServiceList;
use App\RemoteService\RemoteServiceSpotify;
use App\RemoteService\RemoteServiceHelper;

#[IsGranted('ROLE_USER')]
final class SpotifyController extends AbstractController
{
    #[Route('/spotify/authorise', name: 'spotify_authorise')]
    public function authorise(#[CurrentUser] ?User $user): Response
    {

        return $this->render('spotify/authorise.html.twig', [
            'user' => $user
        ]);
    }
}
