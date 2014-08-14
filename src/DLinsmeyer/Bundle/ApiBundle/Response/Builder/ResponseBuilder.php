<?php
/**
 * ResponseBuilder.php
 *
 * @package DLinsmeyer\Bundle\ApiBundle\Response\Builder
 */

namespace DLinsmeyer\Bundle\ApiBundle\Response\Builder;

use DLinsmeyer\Bundle\ApiBundle\Exception\InvalidBuilderConfigurationException;
use DLinsmeyer\Bundle\ApiBundle\Response\Model\Response;
use DLinsmeyer\Bundle\ApiBundle\Response\Model\ResponseInterface;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\AbstractResponse;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\Enum\ResponseType;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\JsonResponse;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\XmlResponse;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\YmlResponse;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

/**
 * Handles constructing a response model
 *
 * @package DLinsmeyer\Bundle\ApiBundle\Response\Builder
 * @author  Daniel Lakes <dlakes@nerdery.com>
 */
class ResponseBuilder implements ResponseBuilderInterface
{
    /**
     * Used for notifying user of invalid response format
     *
     * @const
     * @var string
     */
    const INVALID_RESPONSE_FORMAT_CONFIG = 'Specified response type %s is not a supported type. Expected one of %s';

    /**
     * the response which we are constructing
     *
     * @var ResponseInterface
     */
    protected $responseModel;

    /**
     * Serializer used for building response
     *
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * expected format of response
     *
     * @var string
     */
    protected $format;

    /**
     * API version for which response should be built
     *
     * @var string
     */
    protected $version;

    /**
     * Groups for which response should be built
     *
     * @var string
     */
    protected $groups;

    /**
     * Used for storing known list of response types
     *
     * @var array|AbstractResponse[]
     */
    protected $responseTypes;

    /**
     * Constructor
     *
     * @param SerializerInterface $serializerInterface - serializer for building our response
     */
    public function __construct(SerializerInterface $serializerInterface)
    {
        $this->responseModel = new Response();
        $this->responseTypes = array();
    }

    /**
     * {@inheritdoc}
     */
    public function setSuccess($success)
    {
        $this->responseModel->setSuccess($success);
    }

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        $this->responseModel->setData($data);
    }

    /**
     * {@inheritdoc}
     */
    public function setMessage($message)
    {
        $this->responseModel->setMessage($message);
    }

    /**
     * {@inheritdoc}
     */
    public function setCode($code)
    {
        $this->responseModel->setCode($code);
    }

    /**
     * {@inheritdoc}
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormat()
    {
        return $this->format;
    }


    /**
     * {@inheritdoc}
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;
    }

    /**
     * {@inheritdoc}
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * {@inheritdoc}
     */
    public function addResponseType($typeKeyStr, AbstractResponse $responseType)
    {
        $this->responseTypes[$typeKeyStr] = $responseType;
    }

    /**
     * {@inheritdoc}
     */
    public function buildResponse()
    {
        $version = $this->getVersion();
        if (empty($version)) {
            throw new InvalidBuilderConfigurationException('No version specified.');
        }

        if (empty($this->responseTypes)) {
            throw new InvalidBuilderConfigurationException('No known response types specified.');
        }

        $format = $this->getFormat();

        if (!array_key_exists($format, $this->responseTypes)) {
            $responseTypesStr = implode(',', $this->responseTypes);
            throw new InvalidBuilderConfigurationException(
                sprintf(
                    'Specified response type %s is not a supported type. Expected one of %s',
                    $format,
                    $responseTypesStr
                )
            );
        }

        $groups = $this->getGroups();

        $serializationContext = SerializationContext::create()
                                                    ->setVersion($version)
                                                    ->setSerializeNull(true)
                                                    ->enableMaxDepthChecks();

        if (null !== $groups) {
            $serializationContext->setGroups($$groups);
        }

        $serializedResponseData = $this->serializer->serialize(
            $this->responseModel,
            $format,
            $serializationContext
        );

        $response = $this->responseTypes[$format]->setContent(
            $serializedResponseData
        );

        return $response;
    }
}
 