<?php

namespace App\RemoteService;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use App\Entity\User;

#[AutoconfigureTag('app.remote_service')]
interface RemoteServiceInterface
{
    public function getName(): string;
    public function getDescription(): string;
    public function authenticate(User $forUser): bool;
}
