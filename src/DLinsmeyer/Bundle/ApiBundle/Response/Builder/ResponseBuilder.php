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
use DLinsmeyer\Bundle\ApiBundle\Serializer\SerializerAdapterInterface;

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
     * @var SerializerAdapterInterface
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
    public function __construct(ResponseInterface $prototype, SerializerAdapterInterface $serializer)
    {
        $this->responseModel = clone $prototype;
        $this->serializer = $serializer;
        $this->responseTypes = array();
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseModel()
    {
        return $this->responseModel;
    }

    /**
     * {@inheritdoc}
     */
    public function getSerializer()
    {
        return $this->serializer;
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
    public function hasResponseType($typeKeyStr)
    {
        return array_key_exists($typeKeyStr, $this->responseTypes);
    }

    /**
     * {@inheritdoc}
     */
    public function buildResponse()
    {
        if (empty($this->responseTypes)) {
            throw new InvalidBuilderConfigurationException('No known response types specified.');
        }

        $format = $this->getFormat();

        if (!$this->hasResponseType($format)) {
            $responseTypesStr = implode(',', $this->responseTypes);
            throw new InvalidBuilderConfigurationException(
                sprintf(
                    'Specified response type %s is not a supported type. Expected one of: %s',
                    $format,
                    $responseTypesStr
                )
            );
        }

        $serializedData = $this->getSerializer()->serialize($this);

        $response = $this->responseTypes[$format]->setContent($serializedData);

        return $response;
    }
}
