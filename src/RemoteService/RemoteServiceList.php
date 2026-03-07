<?php

namespace App\RemoteService;

class RemoteServiceList
{
    /**
     * @var RemoteServiceInterface[]
     */
    private array $services = [];

    public function addService(RemoteServiceInterface $service): void
    {
        $this->services[] = $service;
    }
    public function getServiceNames(): array
    {
        return array_map(fn(RemoteServiceInterface $service) => $service->getName(), $this->services);
    }
}
