<?php

namespace Core\Interfaces;

interface MessageFactory
{

    /**
     * Get message collection without source and destination
     * @param array $messages Messages array
     * @param array $sources Sources as "web", "app", "mobile", "system" etc
     * @return MessageCollectionFlash
     */
    public function getMessageCollection(array $messages = [], array $sources = []): MessageCollectionFlash;

    /**
     * Get message collection with $_SESSION source and destination
     * @param array $messages Messages array
     * @param array $sources Sources as "web", "app", "mobile", "system" etc
     * @return MessageCollectionFlash
     */
    public function getMessagesSession(array $messages = [], array $sources = []): MessageCollectionFlash;

    /**
     * Get message collection with $_COOKIES source and destination
     * @param array $messages Messages array
     * @param array $sources Sources as "web", "app", "mobile", "system" etc
     * @return MessageCollectionFlash
     */
    public function getMessagesCookie(array $messages = [], array $sources = []): MessageCollectionFlash;
}
