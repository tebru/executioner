<?php
/**
 * File LinearWaitStrategyTest.php
 */

namespace Tebru\Executioner\Test\Strategy\Wait;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Strategy\Wait\LinearWaitStrategy;

/**
 * Class LinearWaitStrategyTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class LinearWaitStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testLinearWait()
    {
        $wait = new LinearWaitStrategy();
        $this->assertEquals(1, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(2, $wait->getWaitTime());
    }

    public function testStartingWait()
    {
        $wait = new LinearWaitStrategy(2);
        $this->assertEquals(2, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(3, $wait->getWaitTime());
    }

    public function testIncrement()
    {
        $wait = new LinearWaitStrategy(1, 2);
        $this->assertEquals(1, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(3, $wait->getWaitTime());
    }

    public function testReset()
    {
        $wait = new LinearWaitStrategy(2);
        $wait->incrementWait();
        $wait->reset();
        $this->assertEquals(2, $wait->getWaitTime());
    }
}
