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

#[IsGranted('ROLE_USER')]
final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]

    public function index(#[CurrentUser] ?User $user, RemoteServiceList $remoteServiceList, RemoteServiceSpotify $spotify): Response
    {
        $spotify->authenticate($user);
        return $this->render('dashboard/index.html.twig', [
            'remote_services' => $remoteServiceList->getServiceNamesByAlias(),
            'user_name' => $user->getUserIdentifier()
        ]);
    }
}
