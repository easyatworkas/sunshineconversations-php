<?php

namespace EasyAtWork\SunshineConversations\SmoochApiClient;

use EasyAtWork\SunshineConversations\SmoochApiClient\Exceptions\SmoochApiException;
use EasyAtWork\SunshineConversations\SmoochApiClient\Psr7\SmoochApiPaginatedResponse;
use EasyAtWork\SunshineConversations\SmoochApiClient\Psr7\SmoochApiResponse;

class SmoochApp
{
    /** @var SmoochApiClient */
    protected $client;

    /** @var string */
    protected $appId;

    /**
     * @param SmoochApiClient $client
     * @param string $appId
     */
    public function __construct(SmoochApiClient $client, string $appId)
    {
        $this->client = $client;
        $this->appId = $appId;
    }

    /**
     * @param string $userIdOrExternalId
     * @return SmoochApiResponse
     * @throws SmoochApiException
     */
    public function getUser(string $userIdOrExternalId): SmoochApiResponse
    {
        return $this->client->get("/apps/{$this->appId}/users/{$userIdOrExternalId}");
    }

    /**
     * @param string $userExternalId
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @return SmoochApiResponse
     * @throws SmoochApiException
     */
    public function createUser(string $userExternalId, string $firstName, string $lastName, string $email): SmoochApiResponse
    {
        return $this->client->post("/apps/{$this->appId}/users", [], [
            'externalId' => $userExternalId,
            'profile' => [
                'givenName' => $firstName,
                'surname' => $lastName,
                'email' => $email,
            ],
        ]);
    }

    /**
     * @param string $userIdOrExternalId
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @return SmoochApiResponse
     * @throws SmoochApiException
     */
    public function updateUser(string $userIdOrExternalId, string $firstName, string $lastName, string $email): SmoochApiResponse
    {
        return $this->client->patch("/apps/{$this->appId}/users", [], [
            'externalId' => $userIdOrExternalId,
            'profile' => [
                'givenName' => $firstName,
                'surname' => $lastName,
                'email' => $email,
            ],
        ]);
    }

    /**
     * @param string $userId
     * @return SmoochApiResponse
     * @throws SmoochApiException
     */
    public function createConversation(string $userId): SmoochApiResponse
    {
        return $this->client->post("/apps/{$this->appId}/conversations", [], [
            'type' => 'personal',
            'participants' => [
                [
                    'userId' => $userId,
                ],
            ],
        ]);
    }

    /**
     * @param string $userIdOrExternalId
     * @param bool $isExternalId
     * @param array $parameters
     * @return SmoochApiPaginatedResponse
     * @throws SmoochApiException
     */
    public function listConversations(string $userIdOrExternalId, bool $isExternalId = false, array $parameters = []): SmoochApiPaginatedResponse
    {
        $idField = $isExternalId ? 'userExternalId' : 'userId';

        return $this->client->getPaginated("/apps/{$this->appId}/conversations", array_merge([
            "filter[{$idField}]" => $userIdOrExternalId,
        ], $parameters));
    }

    /**
     * @param string $conversationId
     * @param array $parameters
     * @return SmoochApiPaginatedResponse
     * @throws SmoochApiException
     */
    public function listMessages(string $conversationId, array $parameters = []): SmoochApiPaginatedResponse
    {
        return $this->client->getPaginated("/apps/{$this->appId}/conversations/{$conversationId}/messages", $parameters);
    }

    /**
     * @param string $conversationId
     * @param string $userId
     * @param string $content
     * @return SmoochApiResponse
     * @throws SmoochApiException
     */
    public function createMessageAsUser(string $conversationId, string $userId, string $content): SmoochApiResponse
    {
        return $this->client->post("/apps/{$this->appId}/conversations/{$conversationId}/messages", [], [
            'author' => [
                'type' => 'user',
                'userId' => $userId,
            ],
            'content' => [
                'type' => 'text',
                'text' => $content,
            ],
        ]);
    }

    /**
     * @param string $conversationId
     * @param string $content
     * @return SmoochApiResponse
     * @throws SmoochApiException
     */
    public function createMessageAsBusiness(string $conversationId, string $content): SmoochApiResponse
    {
        return $this->client->post("/apps/{$this->appId}/conversations/{$conversationId}/messages", [], [
            'author' => [
                'type' => 'business',
            ],
            'content' => [
                'type' => 'text',
                'text' => $content,
            ],
        ]);
    }

    /**
     * @param string $conversationId
     * @param string $content
     * @return SmoochApiResponse
     * @throws SmoochApiException
     */
    public function createMessageAsAi(string $conversationId, string $content): SmoochApiResponse
    {
        return $this->client->post("/apps/{$this->appId}/conversations/{$conversationId}/messages", [], [
            'author' => [
                'type' => 'business',
                'subtypes' => [
                    'AI',
                ],
            ],
            'content' => [
                'type' => 'text',
                'text' => $content,
            ],
        ]);
    }
}
