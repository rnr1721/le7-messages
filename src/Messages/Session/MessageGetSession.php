<?php

namespace Core\Messages\Session;

use Core\Interfaces\SessionInterface;
use Core\Interfaces\MessageGetInterface;

class MessageGetSession implements MessageGetInterface
{

    private string $sessionKey = 'flash_msg';
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
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
