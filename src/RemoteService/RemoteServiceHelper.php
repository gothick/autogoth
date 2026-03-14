<?php

namespace App\RemoteService;
use App\Entity\User;
use App\Entity\RemoteAuthorisation;
use App\RemoteService\RemoteServiceList;

class RemoteServiceHelper
{
    public function __construct(
        private readonly RemoteServiceList $remoteServiceList
    )
    {
    }
    public function getServiceByAlias(string $alias): ?RemoteServiceInterface
    {
        return $this->remoteServiceList->getServiceByAlias($alias);
    }
    public function getAvailableServicesForUser(User $user): array
    {
        $services = $this->remoteServiceList->getServiceNamesByAlias();

        /** @var string[] $authorisedAliases */
        $authorisedAliases = [];

        $user->getRemoteAuthorisations()->map(function (RemoteAuthorisation $authorisation) use (&$authorisedAliases) {
            $authorisedAliases[] = $authorisation->getRemoteServiceAlias();
        });

        // Subtract the authorised aliases from the full list of services to get the available services
        $services = array_diff_key($services, array_flip($authorisedAliases));

        return $services;
    }
    public function getConfiguredServicesForUser(User $user): array
    {
        $services = $this->remoteServiceList->getServiceNamesByAlias();

        /** @var string[] $authorisedAliases */
        $authorisedAliases = [];

        $user->getRemoteAuthorisations()->map(function (RemoteAuthorisation $authorisation) use (&$authorisedAliases) {
            $authorisedAliases[] = $authorisation->getRemoteServiceAlias();
        });

        // Filter the services to only include the authorised aliases
        $services = array_intersect_key($services, array_flip($authorisedAliases));

        return $services;
    }
}
