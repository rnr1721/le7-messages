<?php

declare(strict_types=1);

namespace Core\Interfaces;

interface MessageCollection
{

    /**
     * Create message of something type
     * @param string $message Message text
     * @param string $status Message status
     * @param string $source Message source 
     * @return self
     */
    public function newMsg(string $message, string $status = 'info', string $source = 'application'): self;

    /**
     * Get all messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getAll(bool $plain = false): array;

    /**
     * Get info messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getInfos(bool $plain = false): array;

    /**
     * Get warning messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getWarnings(bool $plain = false): array;

    /**
     * Get question messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getQuestions(bool $plain = false): array;

    /**
     * Get alert messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getAlerts(bool $plain = false): array;

    /**
     * Get error messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getErrors(bool $plain = false): array;

    /**
     * Get messages by source
     * @param string $source Actual source from sources
     * @return array
     */
    public function getBySource(string $source, bool $plain = false): array;

    /**
     * Emit alert message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollection
     */
    public function alert(string $message, string $source = "application"): self;

    /**
     * Emit warning message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollection
     */
    public function warning(string $message, string $source = "application"): self;

    /**
     * Emit question message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollection
     */
    public function question(string $message, string $source = "application"): self;

    /**
     * Emit info message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollection
     */
    public function info(string $message, string $source = "application"): self;

    /**
     * Emit error message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollection
     */
    public function error(string $message, string $source = "application"): self;

    /**
     * Get messages somewhere
     * @param MessageGet|null $getMethod Instance of messageGet interface
     * @param string $type Type of messages or all
     * @return array
     */
    public function getFromSource(?MessageGet $getMethod = null, string $type = 'all'): array;

    /**
     * Some as getMessages, but messages load to current messages list
     * @param MessageGet|null $getMethod Interface to load
     * @param string $type Message type
     * @return bool
     */
    public function loadMessages(?MessageGet $getMethod = null, string $type = 'all'): bool;

    /**
     * Clear all messages
     * @return void
     */
    public function clear(): void;

    /**
     * Messages empty? bool.
     * @return bool
     */
    public function isEmpty(): bool;
}
