<?php

declare(strict_types=1);

namespace Core\Messages;

use Core\Interfaces\MessageGet;
use Core\Interfaces\MessagePut;
use Core\Interfaces\MessageCollection;
use \Exception;
use function in_array,
             count,
             array_merge;

class MessageCollectionGeneric implements MessageCollection
{

    private ?MessageGet $messageGet = null;
    private ?MessagePut $messagePut = null;
    private array $msgTypes = array(
        'info',
        'error',
        'question',
        'alert',
        'warning'
    );
    private array $msgSources = array(
        'core',
        'application',
        'user',
        'event',
        'system'
    );
    private array $messages;

    public function __construct(array $messages = [], array $msgSources = [], ?MessageGet $messageGet = null, ?MessagePut $messagePut = null)
    {
        $this->messages = $messages;
        $this->messageGet = $messageGet;
        $this->messagePut = $messagePut;
        if (count($msgSources) !== 0) {
            $this->msgSources = $msgSources;
        }
    }

    /**
     * Create message of something type
     * @param string $message Message text
     * @param string $status Message status
     * @param string $source Message source
     * @return self
     */
    public function newMsg(string $message, string $status = 'info', string $source = 'application'): self
    {
        $this->checkSource($source);
        $this->checkType($status);
        $this->messages[] = [
            'type' => $status,
            'message' => $message,
            'source' => $source
        ];
        return $this;
    }

    /**
     * Get all messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getAll(bool $plain = false): array
    {
        if ($plain) {
            $result = array();
            foreach ($this->messages as $message) {
                $result[] = $message['message'];
            }
            return $result;
        }
        return $this->messages;
    }

    public function getBySource(string $source, bool $plain = false): array
    {
        $result = [];
        foreach ($this->messages as $message) {
            if ($message['source'] === $source) {
                if ($plain) {
                    $result[] = $message['message'];
                } else {
                    $result[] = $message;
                }
            }
        }
        return $result;
    }

    /**
     * Get info messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getInfos(bool $plain = false): array
    {
        return $this->getSpecial('info', $plain);
    }

    /**
     * Get warning messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getWarnings(bool $plain = false): array
    {
        return $this->getSpecial('warning', $plain);
    }

    /**
     * Get question messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getQuestions(bool $plain = false): array
    {
        return $this->getSpecial('question', $plain);
    }

    /**
     * Get alert messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getAlerts(bool $plain = false): array
    {
        return $this->getSpecial('alert', $plain);
    }

    /**
     * Get error messages as array
     * @param bool $plain For plain - simple array $key=>$value
     * @return array
     */
    public function getErrors(bool $plain = false): array
    {
        return $this->getSpecial('error', $plain);
    }

    /**
     * Get messages by type as array - full or plain
     * @param string $type Message type
     * @param bool $plain Full or plain array
     * @return array
     */
    private function getSpecial(string $type, bool $plain = false): array
    {
        $result = array();
        $this->checkType($type);
        foreach ($this->messages as $message) {
            if ($message['type'] === $type) {
                if ($plain) {
                    $result[] = $message['message'];
                } else {
                    $result[] = $message;
                }
            }
        }
        return $result;
    }

    /**
     * Check if message type is correct
     * @param string $type Message type
     * @return void
     */
    private function checkType(string $type): void
    {
        if (!in_array($type, $this->msgTypes)) {
            throw new Exception(_('Message type not correct:') . $type);
        }
    }

    /**
     * Checks if message source can be used
     * @param string $source Message source for grouping
     * @return void
     */
    private function checkSource(string $source): void
    {
        if (!in_array($source, $this->msgSources)) {
            throw new Exception(_('Message source not correct:') . $source);
        }
    }

    /**
     * Emit alert message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollection
     */
    public function alert(string $message, string $source = "application"): MessageCollection
    {
        $this->newMsg($message, 'alert', $source);
        return $this;
    }

    /**
     * Emit error message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollection
     */
    public function error(string $message, string $source = "application"): MessageCollection
    {
        $this->newMsg($message, 'error', $source);
        return $this;
    }

    /**
     * Emit question message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollection
     */
    public function question(string $message, string $source = "application"): MessageCollection
    {
        $this->newMsg($message, 'question', $source);
        return $this;
    }

    /**
     * Emit warning message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollection
     */
    public function warning(string $message, string $source = "application"): MessageCollection
    {
        $this->newMsg($message, 'warning', $source);
        return $this;
    }

    /**
     * Emit info message
     * @param string $message Message text
     * @param string $source Source
     * @return MessageCollection
     */
    public function info(string $message, string $source = "application"): MessageCollection
    {
        $this->newMsg($message, 'info', $source);
        return $this;
    }

    /**
     * Put messages to somewhere using interface
     * @param MessagePut $putMethod
     * @param string $type
     * @return bool
     */
    public function putToDestination(?MessagePut $putMethod = null, string $type = 'all'): bool
    {
        if ($putMethod === null) {
            $putMethod = $this->messagePut;
        }
        if (!$putMethod instanceof MessagePut) {
            throw new Exception('MessageCollectionGeneric::getFromSource() no destination');
        }
        if ($type === 'all') {
            $messages = $this->messages;
        } else {
            $messages = $this->getSpecial($type);
        }
        return $putMethod->putMessages($messages);
    }

    /**
     * Get messages from provider (MessageGetInterface)
     * @param MessageGet $getMethod
     * @param string $type
     * @return array
     * @throws Exception
     */
    public function getFromSource(?MessageGet $getMethod = null, string $type = 'all'): array
    {
        if ($getMethod === null) {
            $getMethod = $this->messageGet;
        }
        if (!$getMethod instanceof MessageGet) {
            throw new Exception('MessageCollectionGeneric::getFromSource() no source');
        }
        $messages = $getMethod->getMessages();
        if ($type === 'all') {
            return $messages;
        }
        $result = [];
        foreach ($messages as $message) {
            if ($message['type'] === $type) {
                $result[] = $message;
            }
        }
        return $result;
    }

    /**
     * Load messages to current
     * @param MessageGet|null $getMethod
     * @param string $type
     * @return bool
     */
    public function loadMessages(?MessageGet $getMethod = null, string $type = 'all'): bool
    {
        $messages = $this->getFromSource($getMethod, $type);
        if (empty($messages)) {
            return false;
        }
        $this->messages = array_merge($this->messages, $messages);
        return true;
    }

    /**
     * Clear all messages
     */
    public function clear(): void
    {
        $this->messages = array();
    }

    /**
     * Is messages empty?
     * @return bool
     */
    public function isEmpty(): bool
    {
        if (count($this->messages) === 0) {
            return true;
        }
        return false;
    }

}
