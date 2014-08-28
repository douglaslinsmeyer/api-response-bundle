<?php
/**
 * ResponseBuilder.php
 *
 * @package DLinsmeyer\Bundle\ApiBundle\Response\Builder
 */

namespace DLinsmeyer\Bundle\ApiBundle\Response\Builder;

use DLinsmeyer\Bundle\ApiBundle\Exception\InvalidBuilderConfigurationException;
use DLinsmeyer\Bundle\ApiBundle\Response\Model\ResponseInterface;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\AbstractResponse;
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
     * {@inheritdoc}
     */
    public function __construct(ResponseInterface $prototype, SerializerInterface $serializer)
    {
        $this->responseModel = clone    $prototype;
        $this->serializer = $serializer;
        $this->responseTypes = array();
    }

    /**
     * {@inheritdoc}
     */
    public function setSuccess($success)
    {
        $this->responseModel->setSuccess($success);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        $this->responseModel->setData($data);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setErrors($errors)
    {
        $this->responseModel->setErrors($errors);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setMessage($message)
    {
        $this->responseModel->setMessage($message);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setCode($code)
    {
        $this->responseModel->setCode($code);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
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

        return $this;
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

        return $this;
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

        return $this;
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
                    'Specified response type %s is not a supported type. Expected one of: %s',
                    $format,
                    $responseTypesStr
                )
            );
        }

        $groups = $this->getGroups();

        $serializationContext = SerializationContext::create();
        $serializationContext
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
