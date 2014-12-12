<?php

namespace DLinsmeyer\Bundle\ApiBundle\Serializer\Adapter;

use DLinsmeyer\Bundle\ApiBundle\Response\Builder\ResponseBuilderInterface;
use DLinsmeyer\Bundle\ApiBundle\Serializer\SerializerAdapterInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

/**
 * Adapts the JMS serializer for use with this bundle
 *
 * @author Daniel Lakes <dlakes@nerdery.com>
 */
class JmsSerializerAdapter implements SerializerAdapterInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Constructor
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(ResponseBuilderInterface $builtResponse)
    {
        $version = $this->getVersion();
        if (empty($version)) {
            throw new InvalidBuilderConfigurationException('No version specified.');
        }

        $groups = $builtResponse->getGroups();

        $serializationContext = SerializationContext::create();
        $serializationContext
            ->setVersion($builtResponse->getVersion())
            ->setSerializeNull(true)
            ->enableMaxDepthChecks();

        if (null !== $groups) {
            $serializationContext->setGroups($$groups);
        }

        return $this->serializer->serialize(
            $builtResponse->getResponseModel(),
            $builtResponse->getFormat(),
            $serializationContext
        );
    }

}
