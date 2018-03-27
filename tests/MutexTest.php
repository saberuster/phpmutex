<?php

namespace PhpMutex\Tests;


use PhpMutex\Mutex;
use Predis\Client;

/**
 * Class MutexTest
 *
 * @package PhpMutex\Tests
 */
class MutexTest extends \PHPUnit\Framework\TestCase
{
    /** @var Client */
    protected static $redis;

    public static function setUpBeforeClass()
    {
        self::$redis = new Client();
    }


    public function testMutex()
    {
        $mutex = new Mutex(self::$redis, 'mutex');

        $this->assertTrue($mutex->lock() == 1);
        $this->assertTrue($mutex->lock() == 0);
        sleep(1);
        $this->assertTrue($mutex->lock() == 1);
        $this->assertTrue($mutex->unlock() == 1);
        $this->assertTrue($mutex->unlock() == 0);
        $this->assertTrue($mutex->lock(2) == 1);
        sleep(2);
        $this->assertTrue($mutex->unlock() == 0);
    }
}
