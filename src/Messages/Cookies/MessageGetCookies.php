<?php

declare(strict_types=1);

namespace Core\Messages\Cookies;

use Core\Interfaces\Cookie;
use Core\Interfaces\MessageGet;

class MessageGetCookies implements MessageGet
{

    private string $cookieName = 'flash_msg';
    private Cookie $cookies;

    public function __construct(Cookie $cookies)
    {
        $this->cookies = $cookies;
    }

    public function getMessages(): array
    {
        $result = $this->cookies->get($this->cookieName);
        if ($result) {
            $this->cookies->delete($this->cookieName);
            if ($this->isJson($result)) {
                return json_decode($result, true);
            }
        }
        return [];
    }

    private function isJson(string $string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

}
