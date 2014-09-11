<?php
/**
 * File LogarithmicWaitTest.php
 */

namespace Tebru\Executioner\Test\Strategy\Wait;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Strategy\Wait\LogarithmicWait;

/**
 * Class LogarithmicWaitTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class LogarithmicWaitTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultLogWait()
    {
        $wait = new LogarithmicWait();
        $this->assertSame(log(2, 10), $wait->getWaitTime());
    }

    public function testStartingLogWait()
    {
        $wait = new LogarithmicWait(3);
        $this->assertSame(log(3, 10), $wait->getWaitTime());
    }

    public function testIncrementWait()
    {
        $wait = new LogarithmicWait(2, 2);
        $this->assertSame(log(2, 10), $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertSame(log(4, 10), $wait->getWaitTime());
    }

    public function testWillUseBase()
    {
        $wait = new LogarithmicWait(2, 1, 2);
        $this->assertSame(log(2, 2), $wait->getWaitTime());
    }
}
