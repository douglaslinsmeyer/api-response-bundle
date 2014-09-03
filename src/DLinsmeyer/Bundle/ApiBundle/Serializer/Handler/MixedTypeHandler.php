<?php

namespace DLinsmeyer\Bundle\ApiBundle\Serializer\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;

/**
 * [Class desc. text goes here...]
 *
 * @package DLinsmeyer\Bundle\ApiBundle\Serializer\Handler
 * @subpackage
 * @author Daniel Lakes <dlakes@nerdery.com>
 */
class MixedTypeHandler implements SubscribingHandlerInterface
{
    const DETERMINED_TYPE_ARR_KEY = "calculatedType";
    const VALUE_ARR_KEY = "value";

    /**
     * Return format:
     *
     *      array(
     *          array(
     *              'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
     *              'format' => 'json',
     *              'type' => 'DateTime',
     *              'method' => 'serializeDateTimeToJson',
     *          ),
     *      )
     *
     * The direction and method keys can be omitted.
     *
     * @return array
     */
    public static function getSubscribingMethods()
    {
        return array(
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'type' => 'Mixed',
                'format' => 'json',
                'method' => 'serializeMixedTypeToJson',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'type' => 'Mixed',
                'format' => 'json',
                'method' => 'deserializeMixedTypeFromJson',
            )
        );
    }

    public function serializeMixedTypeToJson(JsonSerializationVisitor $visitor, $value, array $type, Context $context)
    {
        if(is_object($value)) {
            $calculatedType = get_class($value);
        } else {
            $calculatedType = gettype($value);
        }

        return array(
            self::VALUE_ARR_KEY => $value,
            self::DETERMINED_TYPE_ARR_KEY => $calculatedType,
        );
    }

    public function deserializeMixedTypeFromJson(JsonSerializationVisitor $visitor, $value, array $type, Context $context)
    {
        $test = true;
    }
}
