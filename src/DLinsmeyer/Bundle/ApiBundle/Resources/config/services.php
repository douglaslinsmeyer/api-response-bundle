<?php

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use \DLinsmeyer\Bundle\ApiBundle\Response\Type\Enum\ResponseType;
use \DLinsmeyer\Bundle\ApiBundle\DependencyInjection\DLinsmeyerApiExtension;

/** @var \Symfony\Component\DependencyInjection\ContainerBuilder $container */
$container->setDefinition(
    'dlinsmeyer_api.response',
    new Definition(
        'DLinsmeyer\Bundle\ApiBundle\Response\Model\Response'
    )
);

$container->setDefinition(
    'dlinsmeyer_api.response_type.json',
    new Definition(
        'DLinsmeyer\Bundle\ApiBundle\Response\Type\JsonResponse'
    )
);
$container->setDefinition(
    'dlinsmeyer_api.response_type.xml',
    new Definition(
        'DLinsmeyer\Bundle\ApiBundle\Response\Type\XmlResponse'
    )
);
$container->setDefinition(
    'dlinsmeyer_api.response_type.yml',
    new Definition(
        'DLinsmeyer\Bundle\ApiBundle\Response\Type\YmlResponse'
    )
);

$responseTypeCallable = function(Definition $definition){
    $definition->addMethodCall(
        'addResponseType',
        array(
            ResponseType::JSON,
            new Reference('response_type_json'),
        )
    )->addMethodCall(
        'addResponseType',
        array(
            ResponseType::XML,
            new Reference('response_type_xml'),
        )
    )->addMethodCall(
        'addResponseType',
        array(
            ResponseType::YML,
            new Reference('response_type_yml'),
        )
    );
};

/*
 * configure the two provided serializers
 */
//JMS
$jmsSerializerAdapterDef = new Definition(
    'DLinsmeyer\Bundle\ApiBundle\Serializer\Adapter\JmsSerializerAdapter',
    array(
        new Reference('serializer')
    )
);
$container->setDefinition(
    'api_response_serializer.jms',
    $jmsSerializerAdapterDef
);

//JSON
$jsonSerializerAdapterDef = new Definition(
    'DLinsmeyer\Bundle\ApiBundle\Serializer\Adapter\JsonEncodeAdapter'
);
$container->setDefinition(
    'api_response_serializer.json',
    $jsonSerializerAdapterDef
);

/**
 * If no serializer is defined, use JMS
 */
if(!$container->hasAlias(DLinsmeyerApiExtension::CONFIGURABLE_SERIALIZER_ALIAS)){
    $container->setAlias(
        DLinsmeyerApiExtension::CONFIGURABLE_SERIALIZER_ALIAS,
        'api_response_serializer.jms'
    );
}

$responseBuilderDef = new Definition(
    'DLinsmeyer\Bundle\ApiBundle\Response\Builder\ResponseBuilder',
    array(
        new Reference('dlinsmeyer_api.response'),
        new Reference(DLinsmeyerApiExtension::CONFIGURABLE_SERIALIZER_ALIAS),
    )
);

/*
 * Add our response types to our builder(s)
 */
$responseTypeCallable($responseBuilderDef);

$container->setDefinition(
    'dlinsmeyer_api.response_builder',
    $responseBuilderDef
);

$container->setAlias(
    'api_response_builder',
    'dlinsmeyer_api.response_builder'
);
$container->setAlias(
    'response_type_json',
    'dlinsmeyer_api.response_type.json'
);
$container->setAlias(
    'response_type_xml',
    'dlinsmeyer_api.response_type.xml'
);
$container->setAlias(
    'response_type_yml',
    'dlinsmeyer_api.response_type.yml'
);

$mixedTypeDefinition = new Definition(
    'DLinsmeyer\Bundle\ApiBundle\Serializer\Handler\MixedTypeHandler'
);
$mixedTypeDefinition->addTag('jms_serializer.subscribing_handler');

$container->setDefinition(
    'dlinsmeyer_api.serializer.handler.mixed_type',
    $mixedTypeDefinition
);
