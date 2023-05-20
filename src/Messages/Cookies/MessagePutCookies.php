<?php

declare(strict_types=1);

namespace Core\Messages\Cookies;

use Core\Interfaces\CookieInterface;
use Core\Interfaces\MessagePutInterface;

class MessagePutCookies implements MessagePutInterface
{

    private string $cookieName = 'flash_msg';
    private CookieInterface $cookies;

    public function __construct(CookieInterface $cookies)
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
