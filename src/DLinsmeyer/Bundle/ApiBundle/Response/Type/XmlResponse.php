<?php
/**
 * File XmlResponse.php
 *
 * @author Douglas Linsmeyer <douglas.linsmeyer@nerdery.com>
 */

namespace DLinsmeyer\Bundle\ApiBundle\Response\Type;

/**
 * Class XmlResponse
 *
 * @package DLinsmeyer\Bundle\WebBundle\Response
 * @author Douglas Linsmeyer <douglas.linsmeyer@nerdery.com>
 */
class XmlResponse extends AbstractResponse
{
    public function __construct($content = '', $status = 200, $headers = array())
    {
        parent::__construct($content, $status, $headers);

        $this->headers->set('Content-Type', 'application/xml');
    }
}
