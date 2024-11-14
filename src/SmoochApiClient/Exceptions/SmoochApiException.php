<?php

namespace EasyAtWork\SunshineConversations\SmoochApiClient\Exceptions;

use EasyAtWork\SunshineConversations\JsonApiClient\Exceptions\JsonApiException;
use EasyAtWork\SunshineConversations\SmoochApiClient\Psr7\SmoochApiResponse;
use Exception;

class SmoochApiException extends Exception
{
    /**
     * @return bool
     */
    public function hasResponse(): bool
    {
        return $this->getResponse() !== null;
    }

    /**
     * @return SmoochApiResponse|null
     */
    public function getResponse()
    {
        $previous = $this->getPrevious();

        if ($previous === null) {
            return null;
        }

        if (!($previous instanceof JsonApiException)) {
            return null;
        }

        return new SmoochApiResponse($previous->getResponse());
    }
}
