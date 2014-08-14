<?php
/**
 * File ResponseDirector.php
 *
 * @package DLinsmeyer\Response\Director
 * @author  Douglas Linsmeyer <douglas.linsmeyer@nerdery.com>
 * @author  Daniel Lakes <dlakes@nerdery.com>
 */

namespace DLinsmeyer\Bundle\ApiBundle\Response\Director;

use DLinsmeyer\Bundle\ApiBundle\Response\Builder\ResponseBuilderInterface;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\AbstractResponse;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\JsonResponse;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\XmlResponse;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\YmlResponse;

/**
 * Class ResponseDirector
 *
 * @package DLinsmeyer\Response\Director
 * @author  Douglas Linsmeyer <douglas.linsmeyer@nerdery.com>
 */
class ResponseDirector
{
    /**
     * The builder used for constructing our response
     *
     * @var ResponseBuilderInterface
     */
    private $responseBuilder;

    /**
     * Constructor
     *
     * @param ResponseBuilderInterface $responseBuilderInterface used to construct response
     */
    public function __construct(ResponseBuilderInterface $responseBuilderInterface)
    {
        $this->responseBuilder = $responseBuilderInterface;

        $this->responseBuilder->setVersion('1.0');
    }

    /**
     * Get the builder for constructing our response
     *
     * @return ResponseBuilderInterface
     */
    public function getResponseBuilder()
    {
        return $this->responseBuilder;
    }

    /**
     * Create a response
     *
     * Uses the {@link $responseBuilder} to create a new response
     *
     * @return AbstractResponse|YmlResponse|XmlResponse|JsonResponse
     */
    public function createResponse()
    {
        return $this->responseBuilder->buildResponse();
    }
}
