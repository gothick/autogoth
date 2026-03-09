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

    /**
     * @return string[]
     */
    public function getServiceNamesByAlias(): array
    {
        $names = [];
        foreach ($this->services as $alias => $service) {
            $names[$alias] = $service->getName();
        }
        return $names;
    }
    public function getService(string $alias): ?RemoteServiceInterface
    {
        return $this->services[$alias] ?? null;
    }
}
