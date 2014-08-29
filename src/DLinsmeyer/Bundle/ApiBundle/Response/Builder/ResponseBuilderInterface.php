<?php
/**
 * ResponseBuilderInterface.php
 * 
 * @package DLinsmeyer\Bundle\ApiBundle\Response\Builder 
 */
 
namespace DLinsmeyer\Bundle\ApiBundle\Response\Builder;

use DLinsmeyer\Bundle\ApiBundle\Exception\InvalidBuilderConfigurationException;
use DLinsmeyer\Bundle\ApiBundle\Exception\InvalidBuilderConfigurationExceptionInterface;
use DLinsmeyer\Bundle\ApiBundle\Response\Model\ResponseInterface;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\AbstractResponse;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\XmlResponse;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\YmlResponse;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Handles constructing a response model.
 *
 * Note: This class will likely strongly mirror the ResponseInterface class,
 * particularly the setter methods defined there.
 *
 * @package DLinsmeyer\Bundle\ApiBundle\Response\Builder

 * @author Daniel Lakes <dlakes@nerdery.com>
 */
interface ResponseBuilderInterface
{
    /**
     * Constructor
     *
     * @param ResponseInterface $prototype the prototype model off of which our response should be based
     * @param SerializerInterface $serializerInterface - serializer for building our response
     */
    public function __construct(ResponseInterface $prototype, SerializerInterface $serializerInterface);

    /**
     * set success
     *
     * @param bool $success success state for the response model
     *
     * @return self
     */
    public function setSuccess($success);

    /**
     * set data
     *
     * @param mixed $data data for the response model
     *
     * @return self
     */
    public function setData($data);

    /**
     * set errors
     *
     * @param mixed $errors errors for the response model
     *
     * @return self
     */
    public function setErrors($errors);
    
    /**
     * set message
     *
     * @param string $message message for the response model
     *
     * @return self
     */
    public function setMessage($message);

    /**
     * set code
     *
     * @param int $code the status code for the response model
     *
     * @return self
     */
    public function setCode($code);

    /**
     * set format
     *
     * @param string $format the expected format of the response, see also: {@see ResponseType}
     *
     * @return self
     * @throws \UnexpectedValueException - thrown when provided format is not one known
     */
    public function setFormat($format);

    /**
     * get the expected format of the response
     *
     * @return self
     */
    public function getFormat();

    /**
     * set version
     *
     * @param string $version expected API version for the response
     *
     * @return self
     */
    public function setVersion($version);

    /**
     * gets the expected API version for the response
     *
     * @return string $version
     */
    public function getVersion();

    /**
     * Set groups
     *
     * @param string $groups group(s) for which the response should be built
     *
     * @return self
     */
    public function setGroups($groups);

    /**
     * Get the group(s) for which the response should be built
     *
     * @return self
     */
    public function getGroups();

    /**
     * Adds a response type to list of supported response types
     *
     * @param string $typeKeyStr the key by which our response should be referred
     * @param AbstractResponse $responseType type of response
     *
     * @return self
     */
    public function addResponseType($typeKeyStr, AbstractResponse $responseType);
    
    /**
     * Build our response based on configured parameters
     *
     * @return AbstractResponse|YmlResponse|XmlResponse|JsonResponse
     * @throws InvalidBuilderConfigurationException thrown if the response is not properly configured
     */
    public function buildResponse();
}
 