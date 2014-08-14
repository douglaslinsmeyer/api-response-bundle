<?php
/**
 * File Response.php
 *
 * @author Douglas Linsmeyer <douglas.linsmeyer@nerdery.com>
 */

namespace DLinsmeyer\Bundle\ApiBundle\Response\Type;

use DLinsmeyer\Bundle\ApiBundle\Response\Model\ResponseInterface;
use Symfony\Component\HttpFoundation\Response ;

/**
 * Class Response
 *
 * @package DLinsmeyer\Bundle\ApiBundle\Response\Type
 * @author Douglas Linsmeyer <douglas.linsmeyer@nerdery.com>
 */
abstract class AbstractResponse extends Response
{
    /**
     * holds our modeled response
     *
     * @var ResponseInterface
     */
    private $responseModel;

    /**
     * Set the responseModel
     *
     * @param ResponseInterface $responseModel the model representing our response
     *
     * @return self
     */
    public function setResponseModel(ResponseInterface $responseModel)
    {
        $this->responseModel = $responseModel;

        return $this;
    }

    /**
     * Get the responseModel
     *
     * @return mixed
     */
    public function getResponseModel()
    {
        return $this->responseModel;
    }
}
