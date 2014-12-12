<?php
/**
 * File ResponseInterface.php
 *
 * @author Douglas Linsmeyer <douglas.linsmeyer@nerdery.com>
 */

namespace DLinsmeyer\Bundle\ApiBundle\Response\Model;

/**
 * Interface ResponseInterface
 *
 * @package DLinsmeyer\Bundle\WebBundle\Api\Response
 * @author Douglas Linsmeyer <douglas.linsmeyer@nerdery.com>
 */
interface ResponseInterface
{
    /**
     * @return bool
     */
    public function getSuccess();

    /**
     * @return mixed
     */
    public function getData();

    /**
     * @return mixed
     */
    public function getErrors();

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @return int
     */
    public function getCode();

    /**
     * @param bool $success
     *
     * @return self
     */
    public function setSuccess($success);

    /**
     * @param mixed $data
     *
     * @return self
     */
    public function setData($data);

    /**
     * @param mixed $errors
     *
     * @return self
     */
    public function setErrors($errors);

    /**
     * @param $message
     *
     * @return self
     */
    public function setMessage($message);

    /**
     * @param int $code
     *
     * @return self
     */
    public function setCode($code);

    /**
     * Returns an array representing our object
     *
     * @return array
     */
    public function toArray();
}
