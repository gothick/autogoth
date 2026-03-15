<?php

namespace App\RemoteService;

use App\Entity\Token\AccessToken;
use App\Entity\Token\RefreshToken;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class RemoteServiceSpotify implements RemoteServiceInterface
{
    public function __construct(
        private readonly string $alias,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }
    public function getName(): string
    {
        return 'Spotify';
    }

    public function getDescription(): string
    {
        return 'Spotify is a digital music service that gives you access to millions of songs.';
    }

    public function getAuthorisationRoute(): string
    {
        return 'spotify_authorise';
    }
    public function getAlias(): string
    {
        return $this->alias;
    }
    public function saveAccessToken(User $user, AccessToken $accessToken): void
    {
        // Save the access token in the database for the user
        // You can use your own implementation here to save the token in your database
        // For example, you can create a new RemoteAuthorisation entity and save it to the database
        $user->getOrAddRemoteAuthorisation($this->alias)
            ->setAccessToken($accessToken);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

    }
    public function saveRefreshToken(User $user, RefreshToken $refreshToken): void
    {
        // Save the refresh token in the database for the user
        // You can use your own implementation here to save the token in your database
        // For example, you can create a new RemoteAuthorisation entity and save it to the database
        $user->getOrAddRemoteAuthorisation($this->alias)
            ->setRefreshToken($refreshToken);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

}
