<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
class WordProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface{

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        
    }
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {

    }
}
