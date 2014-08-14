<?php
/**
 * ResponseBuilder.php
 *
 * @package DLinsmeyer\Bundle\ApiBundle\Response\Builder
 */

namespace DLinsmeyer\Bundle\ApiBundle\Response\Builder;

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
    const ERROR_RESPONSE_FORMAT_UNKNOWN = 'Response format "%s" is unrecognized. Expected one of: %s';

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
     * Constructor
     *
     * @param SerializerInterface $serializerInterface - serializer for building our response
     */
    public function __construct(SerializerInterface $serializerInterface) {
        $this->format = "";
        $this->version = "";
        $this->groups = "";

        $this->responseModel = new Response();
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
        if (!in_array($format, ResponseType::getOptions())) {
            throw new \UnexpectedValueException(
                sprintf(
                    self::ERROR_RESPONSE_FORMAT_UNKNOWN,
                    $format,
                    ResponseType::optionsToString()
                )
            );
        }

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
    public function buildResponse()
    {
        $version = $this->getVersion();
        $version = !empty($version)
            ? $version
            : '1.0';

        $format = $this->getFormat();
        $format = !empty($format)
            ? $format
            : ResponseType::JSON;

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

        switch ($format) {
            case ResponseType::JSON:
                $response = new JsonResponse($serializedResponseData);
                break;

            case ResponseType::XML:
                $response = new XmlResponse($serializedResponseData);
                break;

            case ResponseType::YML:
                $response = new YmlResponse($serializedResponseData);
                break;

            default:
                throw new \UnexpectedValueException(
                    sprintf(
                        self::ERROR_RESPONSE_FORMAT_UNKNOWN,
                        $format,
                        ResponseType::optionsToString()
                    )
                );
        }

        return $response;
    }
}
 