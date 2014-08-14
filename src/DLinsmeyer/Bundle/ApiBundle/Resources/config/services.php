<?php

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use \DLinsmeyer\Bundle\ApiBundle\Response\Type\Enum\ResponseType;
use \DLinsmeyer\Bundle\ApiBundle\Response\Type\JsonResponse;
use \DLinsmeyer\Bundle\ApiBundle\Response\Type\XmlResponse;
use \DLinsmeyer\Bundle\ApiBundle\Response\Type\YmlResponse;

$container->setDefinition(
    'dlinsmeyer_api.response',
    new Definition(
        'DLinsmeyer\Bundle\ApiBundle\Response\Model\Response'
    )
);

$responseBuilderDef = new Definition(
    'DLinsmeyer\Bundle\ApiBundle\Response\Builder\ResponseBuilder'
);
$responseBuilderDef->addMethodCall(
    'setResponseType',
    array(
        ResponseType::JSON,
        new JsonResponse(),
    )
)->addMethodCall(
    'setResponseType',
    array(
        ResponseType::XML,
        new XmlResponse(),
    )
)->addMethodCall(
    'setResponseType',
    array(
        ResponseType::YML,
        new YmlResponse(),
    )
);

$container->setDefinition(
    'dlinsmeyer_api.response_builder',
    $responseBuilderDef
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
