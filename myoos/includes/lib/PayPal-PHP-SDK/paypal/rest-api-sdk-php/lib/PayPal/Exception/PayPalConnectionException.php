<?php

namespace PayPal\Exception;

/**
 * Class PayPalConnectionException
 *
 * @package PayPal\Exception
 */
class PayPalConnectionException extends \Exception
{
    /**
     * Any response data that was returned by the server
     *
     * @var string
     */
    private $data;

    /**
     * Default Constructor
     *
     * @param string $url
     * @param string $message
     * @param int    $code
     */
    public function __construct(private $url, $message, $code = 0)
    {
        parent::__construct($message, $code);
    }

    /**
     * Sets Data
     *
     * @param $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Gets Data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Gets Url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
