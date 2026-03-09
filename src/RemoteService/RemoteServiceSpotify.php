<?php

namespace App\RemoteService;

use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class RemoteServiceSpotify implements RemoteServiceInterface
{
    public function __construct(
        #[Autowire(env: 'SPOTIFY_CLIENT_ID')] public readonly string $clientId,
        #[Autowire(env: 'SPOTIFY_CLIENT_SECRET')] public readonly string $clientSecret
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

    public function authenticate(User $forUser): bool
    {
        // Here you would implement the actual authentication logic with Spotify's API.
        // For demonstration purposes, we'll just return true.
        return true;
    }
}
