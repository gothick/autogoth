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
    public function getAvailableServicesForUser(User $user): array
    {
        $services = $this->remoteServiceList->getServiceNamesByAlias();

        // Build list of remoteServerAliases from the user's remote authorisations
        $authorisedAliases = [];
        foreach ($user->getRemoteAuthorisations() as $authorisation) {
            $authorisedAliases[] = $authorisation->getRemoteServiceAlias();
        }
        // Subtract the authorised aliases from the full list of services to get the available services
        $services = array_diff_key($services, array_flip($authorisedAliases));

        return $services;
    }
}