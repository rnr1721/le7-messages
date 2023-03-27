<?php

declare(strict_types=1);

namespace Core\Interfaces;

interface MessageGet {

    /**
     * Get messages from source as array
     * @return array
     */
    public function getMessages(): array;
}
