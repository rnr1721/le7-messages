<?php

use Psr\SimpleCache\CacheInterface;
use Core\Cache\SCFactoryGeneric;
use Core\Session\SessionCache;
use Core\Cookies\CookiesCache;
use Core\Cookies\CookieConfigDefault;
use Core\Messages\MessageFactoryGeneric;
use Core\Interfaces\MessageCollectionInterface;

require_once 'vendor/autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';

class MessagesTest extends PHPUnit\Framework\TestCase
{

    protected MessageCollectionInterface $msg;
    protected MessageCollectionInterface $msgSession;
    protected MessageCollectionInterface $msgCookies;

    protected function setUp(): void
    {
        $cache = $this->prepareCache();
        $s = new \Core\Session\SessionNative();
        $session = new SessionCache('usr32', $cache);
        $cookies = new CookiesCache(new CookieConfigDefault(), $cache, 'usr37');
        $factory = new MessageFactoryGeneric($cookies, $session);
        $this->msgSession = $factory->getMessagesSession();
        $this->msgCookies = $factory->getMessagesCookie();
        $this->msg = $factory->getMessageCollection([
            [
                'message' => 'testMessage',
                'type' => 'alert',
                'source' => 'application'
            ]
        ]);
    }

    public function testMessagesCollection()
    {

        $this->assertEquals(1, count($this->msg->getAll()));

        $this->msg->clear();

        $this->assertEmpty($this->msg->getAll());

        // Test standard adding messages
        $this->msg->alert('msgAlert');
        $this->msg->error('msgError');
        $this->msg->question('msgQuestion');
        $this->msg->warning('msgWarning');
        $this->msg->info('msgInfo');
        $messageArray = $this->msg->getAll();
        $msgstr = json_encode($messageArray);
        $this->assertEquals('msgAlert', str_contains($msgstr, 'msgAlert'));
        $this->assertEquals('msgAlert', str_contains($msgstr, 'msgError'));
        $this->assertEquals('msgAlert', str_contains($msgstr, 'msgQuestion'));
        $this->assertEquals('msgAlert', str_contains($msgstr, 'msgWarning'));
        $this->assertEquals('msgAlert', str_contains($msgstr, 'msgInfo'));
        $this->msg->clear();
        // Test empty
        $this->assertEquals([], $this->msg->getAll());
        $this->assertTrue($this->msg->isEmpty());
        // Test sources
        $this->msg->alert('msgAlert', 'core');
        $this->msg->error('msgError', 'core');
        $this->msg->question('msgQuestion');
        $this->msg->info('msgInfo');
        $this->msg->warning('msgWarning');
        $messagesBySource = $this->msg->getBySource('core');
        $this->assertEquals(2, count($messagesBySource));

        // Test plain
        $msgAlerts = $this->msg->getAlerts(true);
        $msgErrors = $this->msg->getErrors(true);
        $msgWarnings = $this->msg->getWarnings(true);
        $msgInfos = $this->msg->getInfos(true);
        $msgQuestions = $this->msg->getQuestions(true);

        $this->assertEquals('msgAlert', $msgAlerts[0]);
        $this->assertEquals('msgError', $msgErrors[0]);
        $this->assertEquals('msgQuestion', $msgQuestions[0]);
        $this->assertEquals('msgWarning', $msgWarnings[0]);
        $this->assertEquals('msgInfo', $msgInfos[0]);
    }

    public function testMessageSession()
    {
        $this->runMessageTest($this->msgSession);
    }

    public function testMessageCookies()
    {
        $this->runMessageTest($this->msgCookies);
    }

    public function runMessageTest(MessageCollectionInterface $msg)
    {
        $msg->alert('alertMsg');
        $msg->error('errorMsg');
        $msgSource = $msg->getAll();
        $msg->putToDestination();
        $msgFromSource = $msg->getFromSource();
        $this->assertEquals($msgSource, $msgFromSource);
        $msg->info('msgInfo');
        $msg->putToDestination();
        $msg->clear();
        $msg->loadMessages();
        $this->assertEquals(3, count($msg->getAll()));
    }

    public function prepareCache(): CacheInterface
    {

        $factory = new SCFactoryGeneric();

        $ds = DIRECTORY_SEPARATOR;

        $cacheDir = getcwd() . $ds . 'tests' . $ds . 'cache';

        if (!file_exists($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }

        $cache = $factory->getFileCache($cacheDir);

        $cache->clear();

        return $cache;
    }

}
