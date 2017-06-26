<?php

namespace App\SAML2;

use Psr\Log\LoggerInterface;
use SAML2\Compat\AbstractContainer;

class Container extends AbstractContainer
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function generateId()
    {
        return '_'. sodium_bin2hex(sodium_randombytes_buf(21));
    }

    public function debugMessage($message, $type)
    {
        $this->logger->debug("saml.{$type}", ['message' => $message]);
    }

    public function redirect($url, $data = array())
    {
        $query = [];

        parse_str(parse_url($url)['query'] ?? '', $query);

        $query = array_merge($query, $data);

        redirect($url . '?' . http_build_query($query))->send();
    }

    public function postRedirect($url, $data = array())
    {
        $inputData = [];

        foreach ($data as $name => $value) {
            $inputData = array_flatten($this->prepareData($name, $value));
        }

        view('saml.post', ['url' => $url, 'data' => $inputData]);
    }

    private function prepareData($name, $value)
    {
        if (is_string($value)) {
            return ['name' => $name, 'value' => $value];
        }

        $items = [];

        foreach ($value as $index => $item) {
            $items[] = $this->prepareData($name . '[' . $index . ']', $item);
        }

        return $items;
    }
}