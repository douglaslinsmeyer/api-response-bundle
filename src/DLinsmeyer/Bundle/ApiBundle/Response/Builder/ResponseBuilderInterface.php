<?php
/**
 * ResponseBuilderInterface.php
 * 
 * @package DLinsmeyer\Bundle\ApiBundle\Response\Builder 
 */
 
namespace DLinsmeyer\Bundle\ApiBundle\Response\Builder;

use DLinsmeyer\Bundle\ApiBundle\Response\Model\ResponseInterface;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\AbstractResponse;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\XmlResponse;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\YmlResponse;
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
     * set the success state for the response
     *
     * @param bool $success
     *
     * @return self
     */
    public function setSuccess($success);

    /**
     * set the data for the response
     *
     * @param mixed $data
     *
     * @return self
     */
    public function setData($data);

    /**
     * set the message for the response
     *
     * @param $message
     *
     * @return self
     */
    public function setMessage($message);

    /**
     * set the status code for the response
     *
     * @param int $code
     *
     * @return self
     */
    public function setCode($code);

    /**
     * set the expected format of the response, see also: {@see ResponseType}
     *
     * @param string $format
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
     * Set the version of the API response to return
     *
     * @param string $version
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
     * Set the group(s) for which the response should be built
     *
     * @param string $groups
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
     * Build our response based on configured parameters
     *
     * @return AbstractResponse|YmlResponse|XmlResponse|JsonResponse
     */
    public function buildResponse();
}
 