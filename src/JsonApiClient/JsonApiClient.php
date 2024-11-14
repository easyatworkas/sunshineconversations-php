<?php

namespace EasyAtWork\SunshineConversations\JsonApiClient;

use EasyAtWork\SunshineConversations\HttpClient\Exceptions\HttpException;
use EasyAtWork\SunshineConversations\HttpClient\HttpClient;
use EasyAtWork\SunshineConversations\JsonApiClient\Exceptions\JsonApiException;
use EasyAtWork\SunshineConversations\JsonApiClient\Psr7\JsonApiResponse;

class JsonApiClient
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $baseUri = 'https://api.smooch.io/v2';

    /**
     * @var array<string, string>
     */
    protected $headers = [
        'Content-Type' => 'application/json',
    ];

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->httpClient = new HttpClient($options);
    }

    /**
     * @param string $baseUri
     * @return void
     */
    public function setBaseUri(string $baseUri)
    {
        $this->baseUri = $baseUri;
    }

    /**
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    /**
     * @param string $username
     * @param string $password
     * @return void
     */
    public function basicAuth(string $username, string $password)
    {
        $this->headers['Authorization'] = 'Basic ' . base64_encode($username . ':' . $password);
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $parameters
     * @param array $data
     * @return JsonApiResponse
     * @throws JsonApiException
     */
    public function request(string $method, string $endpoint, array $parameters = [], array $data = []): JsonApiResponse
    {
        try {
            $response = $this->httpClient->request(
                $method,
                $this->getBaseUri() . $endpoint . '?' . http_build_query($parameters),
                [
                    'headers' => $this->headers,
                    'body' => json_encode($data),
                ]
            );

            return new JsonApiResponse($response);
        } catch (HttpException $exception) {
            throw new JsonApiException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
