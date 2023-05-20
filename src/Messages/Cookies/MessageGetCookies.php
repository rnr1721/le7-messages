<?php

declare(strict_types=1);

namespace Core\Messages\Cookies;

use Core\Interfaces\CookieInterface;
use Core\Interfaces\MessageGetInterface;

class MessageGetCookies implements MessageGetInterface
{

    private string $cookieName = 'flash_msg';
    private CookieInterface $cookies;

    public function __construct(CookieInterface $cookies)
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
