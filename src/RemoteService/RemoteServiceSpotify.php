<?php

namespace App\RemoteService;

use App\Entity\User;

class RemoteServiceSpotify implements RemoteServiceInterface
{
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
