<?php

namespace Resque\Tests;

use Monolog\Handler\TestHandler;
use PHPUnit\Framework\TestCase;
use Redis;
use Resque\Resque;

/**
 * Resque test case class. Contains setup and teardown methods.
 *
 * @package		Resque/Tests
 * @author		Chris Boulton <chris@bigcommerce.com>
 * @license		http://www.opensource.org/licenses/mit-license.php
 */
class ResqueTestCase extends TestCase
{
    protected $resque;
    protected $redis;
    protected $logger;
	protected TestHandler $loggerHandler;

    public static function setUpBeforeClass(): void
    {
        date_default_timezone_set('UTC');
    }

    protected function setUp(): void
    {
        $config = file_get_contents(REDIS_CONF);
        preg_match('#^\s*port\s+([0-9]+)#m', $config, $matches);

        $this->redis = new Redis();
		$this->redis->connect('localhost', $matches[1]);

        $this->logger = $this->getMockBuilder('Psr\Log\LoggerInterface')
                             ->getMock();

        Resque::setBackend('redis://localhost:' . $matches[1]);

        // Flush redis
        $this->redis->flushAll();
    }
}
