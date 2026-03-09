<?php

namespace App\RemoteService;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use App\Entity\User;

// Everything that implements this interface will be automatically tagged with "app.remote_service"
// and added to the RemoteServiceList service.
// #[AutoconfigureTag('app.remote_service')]
interface RemoteServiceInterface
{
    public function getName(): string;
    public function getDescription(): string;
    public function authenticate(User $forUser): bool;
}
