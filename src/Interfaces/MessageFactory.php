<?php

namespace Core\Interfaces;

interface MessageFactory
{

    /**
     * Get message collection without source and destination
     * @param array $messages Messages array
     * @param array $sources Sources as "web", "app", "mobile", "system" etc
     * @return MessageCollection
     */
    public function getMessageCollection(array $messages = [], array $sources = []): MessageCollection;

    /**
     * Get message collection with $_SESSION source and destination
     * @param array $messages Messages array
     * @param array $sources Sources as "web", "app", "mobile", "system" etc
     * @return MessageCollection
     */
    public function getMessagesSession(array $messages = [], array $sources = []): MessageCollection;

    /**
     * Get message collection with $_COOKIES source and destination
     * @param array $messages Messages array
     * @param array $sources Sources as "web", "app", "mobile", "system" etc
     * @return MessageCollection
     */
    public function getMessagesCookie(array $messages = [], array $sources = []): MessageCollection;
}
