<?php
/**
 * File YmlResponse.php
 *
 * @author Douglas Linsmeyer <douglas.linsmeyer@nerdery.com>
 */

namespace DLinsmeyer\Bundle\ApiBundle\Response\Type;

/**
 * Class YmlResponse
 *
 * @package DLinsmeyer\Bundle\WebBundle\Response
 * @author Douglas Linsmeyer <douglas.linsmeyer@nerdery.com>
 */
class YmlResponse extends AbstractResponse
{
    public function __construct($content = '', $status = 200, $headers = array())
    {
        parent::__construct($content, $status, $headers);

        $this->headers->set('Content-Type', 'text/x-yaml');
    }
}
