<?php

namespace EasyAtWork\SunshineConversations\SmoochApiClient\Psr7;

use EasyAtWork\SunshineConversations\HttpClient\Psr7\WrappedResponse;
use EasyAtWork\SunshineConversations\JsonApiClient\Exceptions\JsonApiException;

class SmoochApiPaginatedResponse extends WrappedResponse
{
    /** @var SmoochApiResponse */
    protected $response;

    /**
     * @param SmoochApiResponse $response
     */
    public function __construct(SmoochApiResponse $response)
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
     * @return mixed
     * @throws JsonApiException
     */
    public function getData()
    {
        return $this->response->getData();
    }

    /**
     * Are there more pages after this one?
     *
     * @return boolean
     * @throws JsonApiException
     */
    public function hasMore(): bool
    {
        return $this->getJson()['meta']['hasMore'] ?? false;
    }

    /**
     * Returns the request parameters required to request the next page.
     *
     * @param int $size
     * @return array
     * @throws JsonApiException
     */
    public function nextPage(int $size = 25): array
    {
        $data = $this->getData();

        $lastItem = end($data);

        return [
            'page' => [
                'after' => $lastItem['id'],
                'size' => $size,
            ],
        ];
    }

    /**
     * Returns the request parameters required to request the previous page.
     *
     * @param int $size
     * @return array
     * @throws JsonApiException
     */
    public function previousPage(int $size = 25): array
    {
        $data = $this->getData();

        $firstItem = reset($data);

        return [
            'page' => [
                'before' => $firstItem['id'],
                'size' => $size,
            ],
        ];
    }
}
