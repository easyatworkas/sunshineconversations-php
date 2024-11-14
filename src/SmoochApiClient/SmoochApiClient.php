<?php

namespace EasyAtWork\SunshineConversations\SmoochApiClient;

use EasyAtWork\SunshineConversations\JsonApiClient\Exceptions\JsonApiException;
use EasyAtWork\SunshineConversations\JsonApiClient\JsonApiClient;
use EasyAtWork\SunshineConversations\SmoochApiClient\Exceptions\SmoochApiException;
use EasyAtWork\SunshineConversations\SmoochApiClient\Psr7\SmoochApiPaginatedResponse;
use EasyAtWork\SunshineConversations\SmoochApiClient\Psr7\SmoochApiResponse;

class SmoochApiClient
{
    /**
     * @var JsonApiClient
     */
    protected $jsonApiClient;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->jsonApiClient = new JsonApiClient($options);
    }

    /**
     * @param string $uri
     * @return void
     */
    public function setBaseUri(string $uri)
    {
        $this->jsonApiClient->setBaseUri($uri);
    }

    /**
     * @param string $keyId
     * @param string $secret
     * @return void
     */
    public function authenticate(string $keyId, string $secret)
    {
        $this->jsonApiClient->basicAuth($keyId, $secret);
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $parameters
     * @param array $data
     * @return SmoochApiResponse
     * @throws SmoochApiException
     */
    protected function request(string $method, string $endpoint, array $parameters = [], array $data = []): SmoochApiResponse
    {
        try {
            $response = $this->jsonApiClient->request($method, $endpoint, $parameters, $data);

            return new SmoochApiResponse($response);
        } catch (JsonApiException $exception) {
            if ($exception->hasResponse()) {
                return new SmoochApiResponse($exception->getResponse());
            }

            throw new SmoochApiException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @param string $url
     * @param array $parameters
     * @return SmoochApiResponse
     * @throws SmoochApiException
     */
    public function get(string $url, array $parameters = []): SmoochApiResponse
    {
        return $this->request('get', $url, $parameters);
    }

    /**
     * @param string $url
     * @param array $parameters
     * @return SmoochApiPaginatedResponse
     * @throws SmoochApiException
     */
    public function getPaginated(string $url, array $parameters = []): SmoochApiPaginatedResponse
    {
        return new SmoochApiPaginatedResponse($this->get($url, $parameters));
    }

    /**
     * @param string $url
     * @param array $parameters
     * @param array $data
     * @return SmoochApiResponse
     * @throws SmoochApiException
     */
    public function post(string $url, array $parameters = [], array $data = []): SmoochApiResponse
    {
        return $this->request('post', $url, $parameters, $data);
    }

    /**
     * @param string $url
     * @param array $parameters
     * @param array $data
     * @return SmoochApiResponse
     * @throws SmoochApiException
     */
    public function patch(string $url, array $parameters = [], array $data = []): SmoochApiResponse
    {
        return $this->request('patch', $url, $parameters, $data);
    }

    /**
     * @param string $url
     * @param array $parameters
     * @param array $data
     * @return SmoochApiResponse
     * @throws SmoochApiException
     */
    public function delete(string $url, array $parameters = [], array $data = []): SmoochApiResponse
    {
        return $this->request('delete', $url, $parameters, $data);
    }
}
