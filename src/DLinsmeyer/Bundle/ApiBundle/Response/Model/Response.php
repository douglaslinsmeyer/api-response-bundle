<?php
/**
 * File Response.php
 *
 * @author Douglas Linsmeyer <douglas.linsmeyer@nerdery.com>
 */

namespace DLinsmeyer\Bundle\ApiBundle\Response\Model;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class Response
 *
 * @package DLinsmeyer\Response
 * @author Douglas Linsmeyer <douglas.linsmeyer@nerdery.com>
 *
 * @Serializer\ExclusionPolicy("ALL")
 */
class Response implements ResponseInterface
{
    /**
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     *
     * @var bool
     */
    private $success;

    /**
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     *
     * @var int
     */
    private $code;

    /**
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     *
     * @var string
     */
    private $message;

    /**
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     *
     * @var mixed
     */
    private $data;

    /**
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     *
     * @var mixed
     */
    private $errors;

    /**
     * Set the code
     *
     * @param int $code
     *
     * @return self
     */
    public function setCode($code)
    {
        $this->code = (int) $code;

        return $this;
    }

    /**
     * Get the code
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the data
     *
     * @param mixed $data
     *
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the data
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * setter for errors
     *
     * @param array $errors errors for Response
     *
     * @return $this
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * getter for errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Set the message
     *
     * @param string $message
     *
     * @return self
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the success
     *
     * @param boolean $success
     *
     * @return self
     */
    public function setSuccess($success)
    {
        $this->success = (bool) $success;

        return $this;
    }

    /**
     * Get the success
     *
     * @return boolean
     */
    public function getSuccess()
    {
        return $this->success;
    }
}
