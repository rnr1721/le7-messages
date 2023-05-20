<?php

namespace Core\Interfaces;

interface MessageCollectionFlashInterface extends MessageCollectionInterface
{

    /**
     * Put messages to somewhere using interface
     * @param MessagePutInterface|null $putMethod Instance of MessagePut interface
     * @param string $type Type of messages or all
     * @return bool
     */
    public function putToDestination(?MessagePutInterface $putMethod = null, string $type = 'all'): bool;
}
