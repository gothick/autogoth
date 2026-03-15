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
use Symfony\Component\HttpFoundation\Request;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

#[IsGranted('ROLE_USER')]
final class SpotifyController extends AbstractController
{
    #[Route('/spotify/authorise', name: 'spotify_authorise')]
    public function authorise(#[CurrentUser] ?User $user, ClientRegistry $clientRegistry, Request $request): Response
    {
        return $clientRegistry
            ->getClient('spotify') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([
                'streaming',
                'user-read-playback-state',
                'user-modify-playback-state',
                'playlist-read-private',
                'app-remote-control'
            ], []);
    }

    #[Route('/spotify/callback', name: 'connect_spotify_check')]
    public function callback(
        #[CurrentUser] ?User $user,
        ClientRegistry $clientRegistry,
        RemoteServiceSpotify $spotifyService
        ): Response
    {
        /** @var OAuth2ClientInterface $spotifyClient */
        $spotifyClient = $clientRegistry->getClient('spotify');

        try {
            $accessToken = $spotifyClient->getAccessToken();
        } catch (IdentityProviderException $e) {
            // Failed to get the access token
            throw new \Exception('Failed to get access token: ' . $e->getMessage());
        }
        // Can also
        // $spotifyClient->fetchUser();
        // $spotifyClient->getAccessToken();
        // $spotifyClient->getAccessToken()->getToken(); // Get the raw access token string
        // $spotifyClient->getAccessToken()->getExpires(); // Get the expiration time of the token
        // $spotifyClient->getAccessToken()->getRefreshToken(); // Get the refresh token if available
        // $spotifyClient->getAccessToken()->getRefreshToken(); // Get the refresh token if available
        // $spotifyClient->getAccessToken()->getValues(); // Get all token values as an array
        // $spotifyClient->fetchUser() // Fetch the user information from Spotify using the access token
        //  ->toArray(); // Convert the user information to an array
        // $spotifyClient->fetchUserFromToken($accessToken) // Fetch the user information from Spotify using the access token
        //     ->toArray(); // Convert the user information to an array
        // $spotifyClient->getOAuth2Provider()
        //     ->getResourceOwner($accessToken)
        //     ->toArray(); // Get the user information as an array
        $spotifyClient->getOAuth2Provider();

        // Save the access token in the database
        $spotifyService->saveAccessToken($user, $accessToken);

        return $this->redirectToRoute('home');
    }
}
