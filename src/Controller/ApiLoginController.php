<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Doctrine\ORM\EntityManagerInterface;

final class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'app_api_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user, EntityManagerInterface $entityManager): Response
    {
        if (null === $user) {
            return $this->json([
                'message' => 'Missing credentials.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $apiKey = $user->getApiKey();
        if (null === $apiKey) {
            $apiKey = bin2hex(random_bytes(16));
            $user->setApiKey($apiKey);
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->json([
            'user'  => $user->getUserIdentifier(),
            'token' => $apiKey
        ]);
    }
}
