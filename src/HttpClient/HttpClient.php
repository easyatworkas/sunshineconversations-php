<?php

namespace EasyAtWork\SunshineConversations\HttpClient;

use EasyAtWork\SunshineConversations\HttpClient\Exceptions\HttpException;
use EasyAtWork\SunshineConversations\HttpClient\Psr7\HttpResponse;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\GuzzleException;

class HttpClient
{
    /**
     * @var Guzzle
     */
    protected $guzzle;

    /**
     * @var array<string, mixed>
     */
    protected $guzzleConfig = [];

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->guzzleConfig = array_merge($this->guzzleConfig, $options);
    }

    /**
     * @return Guzzle
     */
    protected function getClient(): Guzzle
    {
        if ($this->guzzle === null) {
            $this->guzzle = new Guzzle();
        }

        return $this->guzzle;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @return HttpResponse
     * @throws HttpException
     */
    public function request(string $method, string $url, array $options = []): HttpResponse
    {
        try {
            $response = $this->getClient()->request($method, $url, $options);

            return new HttpResponse($response);
        } catch (GuzzleException $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
