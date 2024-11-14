## Installation

Install it with composer:

```bash
composer require easyatworkas/sunshineconversations
```

## Usage

### Authentication

```php
// Create a client.
$client = new SmoochApiClient();
$client->setBaseUri('https://easyatwork.zendesk.com/sc/v2');
$client->authenticate('your api key', 'your api secret');

// Create an "app".
$app = new SmoochApp($client, 'your app id');
```

### Basics

```php
// Find an existing user.
$user = $app->getUser(3)->getData();

// List their conversations.
$conversations = $app->listConversations($user['id'])->getData();

// Unless multi conversation mode is enabled, each user will only ever have one conversation.
$activeConversation = reset($conversations);

$app->createMessageAsBusiness($activeConversation['id'], 'Hello! How can I help you today?');
```

### Pagination

When you have an instance of `SmoochApiPaginatedResponse` you can invoke `hasMore()` to see if there are more pages available, then use `nextPage()` to generate the request parameters required to fetch the next page. Simply pass these parameters back to the relevant request method.

```php
$messages = [];
$pageParams = [];

do {
    $response = $app->listMessages($conversation['id'], $pageParams);

    $messages = array_merge($messages, $response->getData());
} while ($response->hasMore() && $pageParams = $response->nextPage());

foreach ($messages as $message) {
    echo $message['author']['displayName'] ?? 'Bot', ': ', $message['content']['text'], PHP_EOL;
}
```
