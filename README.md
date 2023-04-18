# le7-messages
This package is probably easy and convenient to implement flash messages using
cookies or session or use without storage as message collection.

It is also possible to make simple, standardized messages for a web page or API
response. Messages can be grouped by source and type. It is possible to apply
your own set of sources or types.

This package use only one dependency - rnr1721/le7-cookie-wrapper for managing
sessions and cookies

Message types (can not set own):

- info
- error
- question
- alert
- warning

predefined sources (can set own):

- core
- application
- user
- event
- system

## Requirements

- PHP 8.1 or higher.
- Composer 2.0 or higher.

## What it can?

- Add new messages to collection
- get messages by source or type
- get messages in full formar (with type and source or as plain array)
- Store messages in session or cookies or get it from these sources

## Installation

```shell
composer require rnr1721/le7-messages
```

## Testing

```shell
composer test
```

## How use?

```php
use Core\Session\SessionNative;
use Core\Cookies\CookiesNative;
use Core\Messages\MessageFactoryGeneric;

    $session = new SessionNative();
    $cookies = new CookiesNative();
    $factory = new MessageFactoryGeneric($cookies, $session);

    $messages = $factory->getMessagesSession();
    // $messages = $factory->getMessagesCookie(); // If need cookies storage
    // $messages = $factory->getMessageCollection(); // if no need storage

    $messages->alert("Alert message");
    $messages->info("Info message");
    $messages->warning("Warning messages");
    $messages->question("Question message");
    $messages->error("error message");

    // And when we need to output all:
    $message->getAll(); //return messages array

    $messages->getErrors(); // Get all errors
    $messages->getErrors(true); // Get all errors as plain array
    $messages->getAlerts(); // Get all alerts
    $messages->getWarnings();
    $messages->getQuestions();
    $messages->getInfos();
    $messages->getErrors();

    // You can use a message contexts.
    // For example:
    $messages->alert('My message','application');
    $messages->warning('my message','core');
    $messages->error('error message','core');

    $messages->getBySource('core'); // Get all with core context
    $messages->getBySource('core', true); // Plain output

    // Put messages to session or to cookies
    $messages->putToDestination();

    // Get messages from session or from cookies as array
    $messages->getFromSource;

    // Load messages from session or from cookies
    $messages->loadMessages();

    // Clear all messages
    $messages->clear();

    // Return if empty messages
    $messages->isEmpty();

```

### What implemented?

```php

<?php

namespace Core\Interfaces;

interface MessageCollection
{

    /**
     * Create message of something type
     * @param string $message Message text
     * @param string $status Message status
     * @param string $source Message source 
     * @return self
     */
    public function newMsg(string $message, string $status = 'info', string $source = 'application'): self;

    /**
     * Get all messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getAll(bool $plain = false): array;

    /**
     * Get info messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getInfos(bool $plain = false): array;

    /**
     * Get warning messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getWarnings(bool $plain = false): array;

    /**
     * Get question messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getQuestions(bool $plain = false): array;

    /**
     * Get alert messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getAlerts(bool $plain = false): array;

    /**
     * Get error messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getErrors(bool $plain = false): array;

    /**
     * Get messages by source
     * @param string $source Actual source from sources
     * @return array
     */
    public function getBySource(string $source, bool $plain = false): array;

    /**
     * Emit alert message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollection
     */
    public function alert(string $message, string $source = "application"): self;

    /**
     * Emit warning message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollection
     */
    public function warning(string $message, string $source = "application"): self;

    /**
     * Emit question message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollection
     */
    public function question(string $message, string $source = "application"): self;

    /**
     * Emit info message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollection
     */
    public function info(string $message, string $source = "application"): self;

    /**
     * Emit error message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollection
     */
    public function error(string $message, string $source = "application"): self;

    /**
     * Get messages somewhere
     * @param MessageGet|null $getMethod Instance of messageGet interface
     * @param string $type Type of messages or all
     * @return array
     */
    public function getFromSource(?MessageGet $getMethod = null, string $type = 'all'): array;

    /**
     * Some as getMessages, but messages load to current messages list
     * @param MessageGet|null $getMethod Interface to load
     * @param string $type Message type
     * @return bool
     */
    public function loadMessages(?MessageGet $getMethod = null, string $type = 'all'): bool;

    /**
     * Clear all messages
     * @return void
     */
    public function clear(): void;

    /**
     * Messages empty? bool.
     * @return bool
     */
    public function isEmpty(): bool;
}

```

```php

<?php

namespace Core\Interfaces;

interface MessageCollectionFlash extends MessageCollection
{

    /**
     * Put messages to somewhere using interface
     * @param MessagePut|null $putMethod Instance of MessagePut interface
     * @param string $type Type of messages or all
     * @return bool
     */
    public function putToDestination(?MessagePut $putMethod = null, string $type = 'all'): bool;

}

```
