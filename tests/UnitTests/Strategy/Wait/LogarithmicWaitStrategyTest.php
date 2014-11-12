<?php
/**
 * File LogarithmicWaitStrategyTest.php
 */

namespace Tebru\Executioner\Test\Strategy\Wait;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Strategy\Wait\LogarithmicWaitStrategy;

/**
 * Class LogarithmicWaitStrategyTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class LogarithmicWaitStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultLogWait()
    {
        $wait = new LogarithmicWaitStrategy();
        $this->assertSame(log(2, 10), $wait->getWaitTime());
    }

    public function testStartingLogWait()
    {
        $wait = new LogarithmicWaitStrategy(3);
        $this->assertSame(log(3, 10), $wait->getWaitTime());
    }

    public function testIncrementWait()
    {
        $wait = new LogarithmicWaitStrategy(2, 2);
        $this->assertSame(log(2, 10), $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertSame(log(4, 10), $wait->getWaitTime());
    }

    public function testWillUseBase()
    {
        $wait = new LogarithmicWaitStrategy(2, 1, 2);
        $this->assertSame(log(2, 2), $wait->getWaitTime());
    }

    public function testReset()
    {
        $wait = new LogarithmicWaitStrategy(2, 2);
        $wait->incrementWait();
        $wait->reset();
        $this->assertSame(log(2, 10), $wait->getWaitTime());

    }
}
