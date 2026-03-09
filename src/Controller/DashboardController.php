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
final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]

    public function index(#[CurrentUser] ?User $user, RemoteServiceHelper $remoteServiceHelper): Response
    {
        $available_services = $remoteServiceHelper->getAvailableServicesForUser($user);

        return $this->render('dashboard/index.html.twig', [
            'available_services' => $available_services,
            'user_name' => $user->getUserIdentifier()
        ]);
    }
}
