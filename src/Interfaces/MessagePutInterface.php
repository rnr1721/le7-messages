<?php

declare(strict_types=1);

namespace Core\Interfaces;

interface MessagePutInterface {

    /**
     * Put message to destination
     * @param array $messages
     * @return bool
     */
    public function putMessages(array $messages): bool;
}
