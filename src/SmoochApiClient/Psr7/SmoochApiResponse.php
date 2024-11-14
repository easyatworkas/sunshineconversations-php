<?php

namespace EasyAtWork\SunshineConversations\SmoochApiClient\Psr7;

use EasyAtWork\SunshineConversations\HttpClient\Psr7\WrappedResponse;
use EasyAtWork\SunshineConversations\JsonApiClient\Exceptions\JsonApiException;
use EasyAtWork\SunshineConversations\JsonApiClient\Psr7\JsonApiResponse;

class SmoochApiResponse extends WrappedResponse
{
    /**
     * @var JsonApiResponse
     */
    protected $response;

    /**
     * @param JsonApiResponse $response
     */
    public function __construct(JsonApiResponse $response)
    {
        parent::__construct($response);
    }

    /**
     * @return mixed
     * @throws JsonApiException
     */
    public function getJson()
    {
        return $this->response->getJson();
    }

    /**
     * Unwrap the requested data from the response.
     *
     * @return mixed
     * @throws JsonApiException
     */
    public function getData()
    {
        $data = $this->getJson();

        $keys = array_diff(array_keys($data), [ 'meta' ]);

        // TODO: Check that we only have 1 key?

        $dataKey = reset($keys);

        return $data[$dataKey];
    }
}
