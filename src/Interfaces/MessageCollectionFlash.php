<?php

namespace Core\Interfaces;

interface MessageCollectionFlash extends MessageCollection
{

    /**
     * Put messages to somewhere using interface
     * @param MessagePut|null $putMethod Instance of MessagePut interface
     * @param string $type Type of messages or all
     * @return bool
     */
    public function putToDestination(?MessagePut $putMethod = null, string $type = 'all'): bool;
}
