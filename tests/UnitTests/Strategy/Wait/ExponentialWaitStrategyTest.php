<?php
/**
 * File ExponentialWaitStrategyTest.php
 */

namespace Tebru\Executioner\Test\Strategy\Wait;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Strategy\Wait\ExponentialWaitStrategy;

/**
 * Class ExponentialWaitStrategyTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ExponentialWaitStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testExponentialWaitDefaults()
    {
        $wait = new ExponentialWaitStrategy();
        $this->assertEquals(1, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(4, $wait->getWaitTime());
    }

    public function testExponentialWait()
    {
        $wait = new ExponentialWaitStrategy(2);
        $this->assertEquals(1, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(4, $wait->getWaitTime());
    }

    public function testExponenetialWaitZeroExponent()
    {
        $wait = new ExponentialWaitStrategy(0);
        $this->assertEquals(1, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(1, $wait->getWaitTime());
    }

    public function testExponenetialWaitOneExponent()
    {
        $wait = new ExponentialWaitStrategy(1);
        $this->assertEquals(1, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(2, $wait->getWaitTime());
    }

    public function testStartingWait()
    {
        $wait = new ExponentialWaitStrategy(2, 2);
        $this->assertEquals(4, $wait->getWaitTime());
    }

    public function testIncrement()
    {
        $wait = new ExponentialWaitStrategy(2, 1, 2);
        $this->assertEquals(1, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(9, $wait->getWaitTime());
    }

    public function testReset()
    {
        $wait = new ExponentialWaitStrategy(2, 2);
        $wait->incrementWait();
        $wait->reset();
        $this->assertEquals(4, $wait->getWaitTime());
    }
}
