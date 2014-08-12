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
    'dlinsmeyer_api.response_factory',
    new Definition(
        'DLinsmeyer\Bundle\ApiBundle\Response\ResponseFactory',
        array(
            new Reference('serializer'),
            new Reference('dlinsmeyer_api.response'),
        )
    )
);

$container->setAlias(
    'api_response_factory',
    'dlinsmeyer_api.response_factory'
);
