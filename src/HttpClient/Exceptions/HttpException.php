<?php

namespace EasyAtWork\SunshineConversations\HttpClient\Exceptions;

use EasyAtWork\SunshineConversations\HttpClient\Psr7\HttpResponse;
use Exception;
use GuzzleHttp\Exception\RequestException;

class HttpException extends Exception
{
    /**
     * @return bool
     */
    public function hasResponse(): bool
    {
        return $this->getResponse() !== null;
    }

    /**
     * @return HttpResponse|null
     */
    public function getResponse()
    {
        $previous = $this->getPrevious();

        if ($previous === null) {
            return null;
        }

        if (!($previous instanceof RequestException)) {
            return null;
        }

        return new HttpResponse($previous->getResponse());
    }
}
