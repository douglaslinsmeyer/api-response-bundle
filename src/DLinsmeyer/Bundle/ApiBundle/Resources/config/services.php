<?php

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

$container->setDefinition(
    'dlinsmeyer_api.response',
    new Definition(
        'DLinsmeyer\Bundle\ApiBundle\Response\Model\Response'
    )
);

$container->setDefinition(
    'dlinsmeyer_api.response_director',
    new Definition(
        'DLinsmeyer\Bundle\ApiBundle\Response\Director\ResponseDirector',
        array(
            new Reference('serializer'),
        )
    )
);

$container->setAlias(
    'api_response_director',
    'dlinsmeyer_api.response_factory'
);
