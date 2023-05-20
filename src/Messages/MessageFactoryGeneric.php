<?php

namespace Core\Messages;

use Core\Interfaces\SessionInterface;
use Core\Interfaces\CookieInterface;
use Core\Interfaces\MessageFactoryInterface;
use Core\Interfaces\MessageCollectionFlashInterface;
use Core\Messages\Cookies\MessageGetCookies;
use Core\Messages\Cookies\MessagePutCookies;
use Core\Messages\Session\MessageGetSession;
use Core\Messages\Session\MessagePutSession;

class MessageFactoryGeneric implements MessageFactoryInterface
{

    private SessionInterface $session;
    private CookieInterface $cookies;

    public function __construct(
            CookieInterface $cookies,
            SessionInterface $session
    )
    {
        $this->cookies = $cookies;
        $this->session = $session;
    }

    public function getMessageCollection(
            array $messages = [],
            array $sources = []
    ): MessageCollectionFlashInterface
    {
        return new MessageCollectionGeneric($messages, $sources);
    }

    public function getMessagesCookie(
            array $messages = [],
            array $sources = []
    ): MessageCollectionFlashInterface
    {
        $messageGet = new MessageGetCookies($this->cookies);
        $messagePut = new MessagePutCookies($this->cookies);
        return new MessageCollectionGeneric(
                $messages,
                $sources,
                $messageGet,
                $messagePut
        );
    }

    public function getMessagesSession(
            array $messages = [],
            array $sources = []
    ): MessageCollectionFlashInterface
    {
        $messageGet = new MessageGetSession($this->session);
        $messagePut = new MessagePutSession($this->session);
        return new MessageCollectionGeneric(
                $messages,
                $sources,
                $messageGet,
                $messagePut
        );
    }

}
