<?php

namespace Core\Messages\Session;

use Core\Interfaces\Session;
use Core\Interfaces\MessagePut;

class MessagePutSession implements MessagePut
{

    private string $sessionKey = 'flash_msg';
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function putMessages(array $messages): bool
    {
        if (!$this->session->isStarted()) {
            $this->session->start();
        }
        if (!empty($messages)) {
            $this->session->set($this->sessionKey, $messages);
            return true;
        }
        $this->session->delete($this->sessionKey);

        return false;
    }

}
