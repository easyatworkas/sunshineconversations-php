<?php

namespace EasyAtWork\SunshineConversations\JsonApiClient\Exceptions;

use EasyAtWork\SunshineConversations\HttpClient\Exceptions\HttpException;
use EasyAtWork\SunshineConversations\JsonApiClient\Psr7\JsonApiResponse;
use Exception;

class JsonApiException extends Exception
{
    /**
     * @return bool
     */
    public function hasResponse(): bool
    {
        return $this->getResponse() !== null;
    }

    /**
     * @return JsonApiResponse|null
     */
    public function getResponse()
    {
        $previous = $this->getPrevious();

        if ($previous === null) {
            return null;
        }

        if (!($previous instanceof HttpException)) {
            return null;
        }

        return new JsonApiResponse($previous->getResponse());
    }
}
