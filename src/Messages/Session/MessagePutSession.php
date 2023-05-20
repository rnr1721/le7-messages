<?php

namespace Core\Messages\Session;

use Core\Interfaces\SessionInterface;
use Core\Interfaces\MessagePutInterface;

class MessagePutSession implements MessagePutInterface
{

    private string $sessionKey = 'flash_msg';
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
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
