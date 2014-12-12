<?php

namespace DLinsmeyer\Bundle\ApiBundle\Serializer;

use DLinsmeyer\Bundle\ApiBundle\Response\Builder\ResponseBuilderInterface;

/**
 * Represents the contact for services that handle serializing responses
 *
 * @author Daniel Lakes <dlakes@nerdery.com>
 */
interface SerializerAdapterInterface
{
    /**
     * @param ResponseBuilderInterface $builtResponse
     *
     * @return string
     */
    public function serialize(ResponseBuilderInterface $builtResponse);
}
