<?php
/**
 * File ResponseType.php
 *
 * @author Douglas Linsmeyer <douglas.linsmeyer@nerdery.com>
 */

namespace DLinsmeyer\Bundle\ApiBundle\Response\Type\Enum;

/**
 * Class ResponseType
 *
 * @package DLinsmeyer\Bundle\WebBundle\Response\Enum
 * @author Douglas Linsmeyer <douglas.linsmeyer@nerdery.com>
 */
class ResponseType
{
    const XML = 'xml';
    const YML = 'yml';
    const JSON = 'json';

    /**
     * Retrieve all response types
     *
     * @return array
     */
    public static function getOptions()
    {
        return [
            self::XML,
            self::YML,
            self::JSON,
        ];
    }

    /**
     * Retrieve all response types as a string
     *
     * @return string
     */
    public static function optionsToString()
    {
        return implode(',', self::getOptions());
    }
}
