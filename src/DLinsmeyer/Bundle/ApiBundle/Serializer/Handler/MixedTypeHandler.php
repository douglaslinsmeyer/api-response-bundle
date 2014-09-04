<?php

namespace DLinsmeyer\Bundle\ApiBundle\Serializer\Handler;

use JMS\Serializer\DeserializationContext;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\SerializationContext;

/**
 * A custom serialization type to handle runtime calculation of
 * type for 'mixed' data fields.
 *
 * @author Daniel Lakes <dlakes@nerdery.com>
 */
class MixedTypeHandler implements SubscribingHandlerInterface
{
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

    /**
     * Given a $value, determines the *actual* type of the data, serializes, and returns
     *
     * @param JsonSerializationVisitor $visitor
     * @param $value
     * @param array $type
     * @param SerializationContext $context
     *
     * @return mixed
     */
    public function serializeMixedTypeToJson(JsonSerializationVisitor $visitor, $value, array $type, SerializationContext $context)
    {
        $isObject = is_object($value);

        $dataType = $this->determineType($value);

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
            return $visitor->getNavigator()->accept($wrappedData, $wrapperArr, $context);
        }
    }

    /**
     * Based on the data type stored in the $value array, deserializes data and returns.
     *
     * @param JsonDeserializationVisitor $visitor
     * @param $value - an array of the format: [type => data], where type is the data type for the data, and data is...data
     * @param array $type
     * @param DeserializationContext $context
     *
     * @return mixed
     */
    public function deserializeMixedTypeFromJson(JsonDeserializationVisitor $visitor, $value, array $type, DeserializationContext $context)
    {
        $test = true;
    }

    /**
     * Given a $value, determines the type of the data.
     *
     * @param mixed $value
     *
     * @return string
     */
    protected function determineType($value)
    {
        return is_object($value) ? get_class($value) : gettype($value);
    }
}
