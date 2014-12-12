<?php

namespace DLinsmeyer\Bundle\ApiBundle\Serializer\Adapter;

use DLinsmeyer\Bundle\ApiBundle\Response\Builder\ResponseBuilderInterface;
use DLinsmeyer\Bundle\ApiBundle\Serializer\SerializerAdapterInterface;

/**
 * Adapts our serializer for using json_encode
 *
 * @author Daniel Lakes <dlakes@nerdery.com>
 */
class JsonEncodeAdapter implements SerializerAdapterInterface
{
    /**
     * Encodes our response model using native json_encode
     *
     * Note: This encoding ignores groups, version, format --
     * basically all things having to do w/ a serialization context
     *
     * It *only* returns the encoded model
     *
     * @param ResponseBuilderInterface $builtResponse
     *
     * @return string
     */
    public function serialize(ResponseBuilderInterface $builtResponse)
    {
        return json_encode($builtResponse->getResponseModel()->toArray());
    }
}
