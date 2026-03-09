<?php

namespace App\RemoteService;

class RemoteServiceList
{
    /**
     * @var RemoteServiceInterface[]
     */
    private array $services = [];

    public function addService(RemoteServiceInterface $service, $alias): void
    {
        $this->services[$alias] = $service;
    }
    public function getServiceAliases(): array
    {
        return array_keys($this->services);
    }
    public function getService(string $alias): ?RemoteServiceInterface
    {
        return $this->services[$alias] ?? null;
    }
}
