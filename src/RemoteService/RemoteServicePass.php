<?php

namespace App\RemoteService;

use App\RemoteService\RemoteServiceList;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RemoteServicePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        // always first check if the primary service is defined
        if (!$container->has(RemoteServiceList::class)) {
            return;
        }

        $definition = $container->findDefinition(RemoteServiceList::class);

        // find all service IDs with the app.remote_service tag
        $taggedServices = $container->findTaggedServiceIds('app.remote_service');

        foreach ($taggedServices as $id => $tags) {

            // a service could have the same tag twice
            foreach ($tags as $attributes) {
                $alias = $attributes['alias'] ?? null;
                if (!$alias) {
                    continue;
                }

                // 1) Inject alias into the tagged service constructor
                $serviceDef = $container->findDefinition($id);
                $serviceDef->setArgument('$alias', $alias);

                $definition->addMethodCall('addService', [
                    new Reference($id),
                    $attributes['alias'],
                ]);
            }
        }
    }
}
