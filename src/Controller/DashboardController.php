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
        $user_services = $remoteServiceHelper->getConfiguredServicesForUser($user);

        return $this->render('dashboard/index.html.twig', [
            'user_services' => $user_services,
            'available_services' => $available_services,
            'user_name' => $user->getUserIdentifier()
        ]);
    }

    #[Route('/connect/{alias}', name: 'connect_service', methods: ['POST'])]
    public function connectService(string $alias, #[CurrentUser] ?User $user, RemoteServiceHelper $remoteServiceHelper): Response
    {
        $service = $remoteServiceHelper->getServiceByAlias($alias);

        if (!$service) {
            // TODO: Add flash handling to Dashboard template
            $this->addFlash('error', 'Service not found.');
            return $this->redirectToRoute('app_dashboard');
        }

        $redirectUrl = $service->getAuthorisationRoute();
        return $this->redirectToRoute($redirectUrl);
    }
}
