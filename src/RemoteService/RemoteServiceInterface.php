<?php

namespace App\RemoteService;

use App\Entity\Token\AccessToken;
use App\Entity\Token\RefreshToken;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use App\Entity\User;

// Everything that implements this interface will be automatically tagged with "app.remote_service"
// and added to the RemoteServiceList service.
// #[AutoconfigureTag('app.remote_service')]
interface RemoteServiceInterface
{
    public function getName(): string;
    public function getDescription(): string;
    public function getAuthorisationRoute(): string;
    public function saveAccessToken(User $user, AccessToken $accessToken): void;
    public function saveRefreshToken(User $user, RefreshToken $refreshToken): void;
    public function getAlias(): string;
}
