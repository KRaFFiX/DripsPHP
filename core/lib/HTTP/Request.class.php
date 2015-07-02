<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 19.03.15 - 12:50.
 */
namespace DripsPHP\HTTP;

use Exception;

/**
 * Class Request.
 *
 * used for sending requests with curl
 */
class Request
{
    protected $connection;

    /**
     * create a new request for $url.
     *
     * @param $url
     *
     * @throws RequestNoCurlException
     */
    public function __construct($url)
    {
        if (function_exists('curl_init')) {
            $this->connection = curl_init();
            $this->set(CURLOPT_RETURNTRANSFER, true);
            $this->set(CURLOPT_URL, $url);
        } else {
            throw new RequestNoCurlException();
        }
    }

    /**
     * disconnect / close curl.
     */
    public function __destruct()
    {
        curl_close($this->connection);
    }

    /**
     * do a post-request with $data.
     *
     * @param $data
     */
    public function post($data)
    {
        $dataStr = '';
        foreach ($data as $key => $value) {
            $dataStr .= "$key=$value&";
        }
        $dataStr = rtrim($dataStr, '&');
        $this->set(CURLOPT_POST, count($data));
        $this->set(CURLOPT_POSTFIELDS, $dataStr);
    }

    /**
     * set options for curl.
     *
     * @param $option
     * @param $value
     */
    public function set($option, $value)
    {
        return curl_setopt($this->connection, $option, $value);
    }

    /**
     * send request!
     *
     * @return string
     */
    public function send()
    {
        return curl_exec($this->connection);
    }
}

class RequestNoCurlException extends Exception
{
}
