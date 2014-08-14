<?php
/**
 * File ResponseDirector.php
 *
 * @author Douglas Linsmeyer <douglas.linsmeyer@nerdery.com>
 */

namespace DLinsmeyer\Bundle\ApiBundle\Response;

use DLinsmeyer\Bundle\ApiBundle\Response\Builder\ResponseBuilderInterface;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\AbstractResponse;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\Enum\ResponseType;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\JsonResponse;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\XmlResponse;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\YmlResponse;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use UnexpectedValueException;

/**
 * Class ResponseDirector
 *
 * @package DLinsmeyer\Response
 * @author  Douglas Linsmeyer <douglas.linsmeyer@nerdery.com>
 */
class ResponseDirector
{
    /**
     * @var ResponseBuilderInterface
     */
    private $responseBuilder;

    /**
     * Constructor
     *
     * @param ResponseBuilderInterface $responseBuilderInterface
     */
    public function __construct(ResponseBuilderInterface $responseBuilderInterface)
    {
        $this->responseBuilder = $responseBuilderInterface;
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
     * @return AbstractResponse|YmlResponse|XmlResponse|JsonResponse
     */
    public function createResponse()
    {
        return $this->responseBuilder->buildResponse();
    }
}
