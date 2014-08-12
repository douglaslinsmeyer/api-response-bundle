<?php
/**
 * File ResponseFactory.php
 *
 * @author Douglas Linsmeyer <douglas.linsmeyer@nerdery.com>
 */

namespace DLinsmeyer\Bundle\ApiBundle\Response;

use DLinsmeyer\Bundle\ApiBundle\Response\Type\AbstractResponse;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\Enum\ResponseType;
use DLinsmeyer\Bundle\ApiBundle\Response\Model\ResponseInterface;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\JsonResponse;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\XmlResponse;
use DLinsmeyer\Bundle\ApiBundle\Response\Type\YmlResponse;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use UnexpectedValueException;

/**
 * Class ResponseFactory
 *
 * @package DLinsmeyer\Response
 * @author Douglas Linsmeyer <douglas.linsmeyer@nerdery.com>
 */
class ResponseFactory
{
    const ERROR_RESPONSE_FORMAT_UNKNOWN = 'Response format "%s" is unrecognized.';

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var ResponseInterface
     */
    private $responseModel;

    /**
     * Constructor
     *
     * @param Serializer $serializer
     * @param ResponseInterface $responseModel
     */
    public function __construct(Serializer $serializer, ResponseInterface $responseModel)
    {
        $this->serializer = $serializer;
        $this->responseModel = $responseModel;
    }

    /**
     * Create a response
     *
     * @param bool $success
     * @param int $code
     * @param string $message
     * @param mixed $data
     * @param string $version
     * @param null|string $groups
     * @param string $format
     *
     * @throws UnexpectedValueException
     * @return AbstractResponse|YmlResponse|XmlResponse|JsonResponse
     */
    public function create($success, $code, $message, $data, $version = '1.0', $groups = null, $format = null)
    {
        if (null === $format) {
            $format = ResponseType::JSON;
        }

        $serializationContext = SerializationContext::create()
            ->setVersion($version)
            ->setSerializeNull(true)
            ->enableMaxDepthChecks();

        if (null !== $groups) {
            $serializationContext->setGroups($groups);
        }

        $responseModel = clone $this->responseModel;
        $responseModel->setSuccess($success);
        $responseModel->setCode($code);
        $responseModel->setMessage($message);
        $responseModel->setData($data);

        $serializedResponseData = $this->serializer->serialize($responseModel, $format, $serializationContext);

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
                throw new UnexpectedValueException(
                    sprintf(self::ERROR_RESPONSE_FORMAT_UNKNOWN, $format)
                );
        }

        return $response;
    }
}
