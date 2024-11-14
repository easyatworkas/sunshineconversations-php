<?php

namespace EasyAtWork\SunshineConversations\JsonApiClient\Psr7;

use EasyAtWork\SunshineConversations\HttpClient\Psr7\WrappedResponse;
use EasyAtWork\SunshineConversations\JsonApiClient\Exceptions\JsonApiException;

class JsonApiResponse extends WrappedResponse
{
    /** @var mixed|null */
    protected $decoded;

    /**
     * @return mixed
     * @throws JsonApiException
     */
    public function getJson()
    {
        if ($this->decoded === null) {
            $this->decoded = json_decode($this->getBody()->getContents(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new JsonApiException(json_last_error_msg(), json_last_error());
            }
        }

        return $this->decoded;
    }
}
