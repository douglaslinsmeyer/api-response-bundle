<?php

namespace DLinsmeyer\Bundle\ApiBundle\Serializer\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\SerializationContext;

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

    public function serializeMixedTypeToJson(JsonSerializationVisitor $visitor, $value, array $type, SerializationContext $context)
    {
        $isObject = is_object($value);
        $dataType = $isObject ? get_class($value) : gettype($value);

        $typeArr = array(
            'name' => $dataType,
            'params' => array()
        );

        /**
         * We need to store the type information along w/ the object on
         * serialization. To do this, we're taking advantage of
         * the array<k,T> type, with k being our class/type expressed as a
         * string, and T being our data
         */
        $wrapperArr = array(
            'name' => 'array',
            'params' => array(
                array(
                    'name' => 'string',
                    'params' => array(),
                ),
                $typeArr
            )
        );
        $wrappedData = array(
            $dataType => $value
        );

        if($isObject){
            /**
             * this has to be done to ensure the serializer will properly parse this value
             * See JMS/Serializer/GraphNavigator.php line 143 (if ($context->isVisiting($data)) [...])
             */
            $context->stopVisiting($value);
            $result = $visitor->getNavigator()->accept($wrappedData, $wrapperArr, $context);
            //now that we're done, reattach so it can handle all of its cleanup
            $context->startVisiting($value);
            return $result;
        } else {
            return $visitor->getNavigator()->accept($value, $wrapperArr, $context);
        }
    }

    public function deserializeMixedTypeFromJson(JsonSerializationVisitor $visitor, $value, array $type, DeserializationContext $context)
    {
        $test = true;
    }
}
