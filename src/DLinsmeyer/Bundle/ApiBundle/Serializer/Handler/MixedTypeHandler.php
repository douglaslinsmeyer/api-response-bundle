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
    /**@#+
     * used for referencing various components of the mixed type serialized array
     *
     * @var string
     * @const
     */
    const RESPONSE_DATA_KEY = 'data';
    const RESPONSE_TYPE_KEY = 'type';
    /**@#0 */

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
     * The way this works is by wrapping the value in an array with 'type' as a property on the array, and then serializing as type==array.
     * On deserialization, we use the type property to determine the actual data structure again to restore the object/value
     *
     * The reason this returns a data result wrapped in an array is to allow for easy consumption by
     * front-end clients that may not be interested in the data type.
     * result.data.blah is much easier to reference than some of our prior approaches such as result.DATA_TYPE.blah,
     * where DATA_TYPE is dynamic depending on the data. This approach gives FE consumers a consistent way to access the data
     * without needing to know the type
     *
     * Due to how the serializer functions, we couldn't create a separate object to store this data, as the $data property would have
     * to be of type==Mixed, and we'd be right back to square one
     *
     * @param JsonSerializationVisitor $visitor
     * @param $value
     * @param array $type
     * @param SerializationContext $context
     *
     * @return array - returns an array of the following format:
     * {
     *      'type' => (string) the type of the data detected
     *      'data' => (mixed) data prepared for serialization
     * }
     */
    public function serializeMixedTypeToJson(JsonSerializationVisitor $visitor, $value, array $type, SerializationContext $context)
    {
        $isObject = is_object($value);

        $dataType = $this->determineType($value);

        $arrayTypeArr = array(
            'name' => 'array',
            'params' => array()
        );

        $wrappedData = array(
            self::RESPONSE_TYPE_KEY => $dataType,
            self::RESPONSE_DATA_KEY => $value,
        );

        if($isObject){
            /*
             * this has to be done to ensure the serializer will properly parse this value
             * See JMS/Serializer/GraphNavigator.php line 143 (if ($context->isVisiting($data)) [...])
             */
            $context->stopVisiting($value);
            $result = $visitor->getNavigator()->accept($wrappedData, $arrayTypeArr, $context);
            //now that we're done, reattach so it can handle all of its cleanup
            $context->startVisiting($value);

            return $result;
        } else {
            $result = $visitor->getNavigator()->accept($wrappedData, $arrayTypeArr, $context);

            return $result;
        }
    }

    /**
     * Based on the data type stored in the $value array, deserializes data and returns.
     *
     * @param JsonDeserializationVisitor $visitor
     * @param array $value - an array of the following format:
     * {
     *      'type' => (string) the type of the data detected
     *      'data' => (mixed) data prepared for serialization
     * }
     * where type is the data type for the data, and data is the actual data to serialize
     *
     * @param array $type
     * @param DeserializationContext $context
     *
     * @return mixed
     */
    public function deserializeMixedTypeFromJson(JsonDeserializationVisitor $visitor, array $value, array $type, DeserializationContext $context)
    {
        $declaredType = $this->extractTypeOnDeserialize($value);
        $extractedData = $value[self::RESPONSE_DATA_KEY];
        $correctTypeArr = array(
            'name' => $declaredType,
            'params' => array(),
        );
        /** @var GraphNavigator $navigator */
        $navigator = $visitor->getNavigator();

        return $navigator->accept($extractedData, $correctTypeArr, $context);
    }

    /**
     * Extracts our declared type from the provided array on deserialization
     *
     * @param array $value
     *
     * @return string
     */
    protected function extractTypeOnDeserialize(array $value)
    {
        return $value[self::RESPONSE_TYPE_KEY];
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
