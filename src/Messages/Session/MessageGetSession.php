<?php

namespace Core\Messages\Session;

use Core\Interfaces\Session;
use Core\Interfaces\MessageGet;

class MessageGetSession implements MessageGet
{

    private string $sessionKey = 'flash_msg';
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getMessages(): array
    {
        if ($this->session->isStarted()) {
            if ($this->session->has($this->sessionKey)) {
                $result = $this->session->get($this->sessionKey);
                $this->session->delete($this->sessionKey);
                return $result;
            }
        }
        return [];
    }

}
