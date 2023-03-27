<?php

namespace Core\Messages;

use Core\Interfaces\Session;
use Core\Interfaces\Cookie;
use Core\Interfaces\MessageFactory;
use Core\Interfaces\MessageCollection;
use Core\Messages\Cookies\MessageGetCookies;
use Core\Messages\Cookies\MessagePutCookies;
use Core\Messages\Session\MessageGetSession;
use Core\Messages\Session\MessagePutSession;

class MessageFactoryGeneric implements MessageFactory
{

    private Session $session;
    private Cookie $cookies;

    public function __construct(Cookie $cookies, Session $session)
    {
        $this->cookies = $cookies;
        $this->session = $session;
    }

    public function getMessageCollection(array $messages = [], array $sources = []): MessageCollection
    {
        return new MessageCollectionGeneric($messages, $sources);
    }

    public function getMessagesCookie(array $messages = [], array $sources = []): MessageCollection
    {
        $messageGet = new MessageGetCookies($this->cookies);
        $messagePut = new MessagePutCookies($this->cookies);
        return new MessageCollectionGeneric($messages, $sources, $messageGet, $messagePut);
    }

    public function getMessagesSession(array $messages = [], array $sources = []): MessageCollection
    {
        $messageGet = new MessageGetSession($this->session);
        $messagePut = new MessagePutSession($this->session);
        return new MessageCollectionGeneric($messages, $sources, $messageGet, $messagePut);
    }

}
