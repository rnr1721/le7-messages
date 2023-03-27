<?php

declare(strict_types=1);

namespace Core\Messages\Cookies;

use Core\Interfaces\Cookie;
use Core\Interfaces\MessagePut;

class MessagePutCookies implements MessagePut
{

    private string $cookieName = 'flash_msg';
    private Cookie $cookies;

    public function __construct(Cookie $cookies)
    {
        $this->cookies = $cookies;
    }

    public function putMessages(array $messages): bool
    {
        if (!empty($messages)) {
            $content = json_encode($messages);
            return $this->cookies->set($this->cookieName, $content);
        }
        $this->cookies->delete($this->cookieName);
        return false;
    }

}
