<?php
/**
 * File FibonacciWaitStrategyTest.php
 */

namespace Tebru\Executioner\Test\Strategy\Wait;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Strategy\Wait\FibonacciWaitStrategy;

/**
 * Class FibonacciWaitStrategyTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class FibonacciWaitStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testFibDefaults()
    {
        $wait = new FibonacciWaitStrategy();
        $this->assertEquals(1, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(2, $wait->getWaitTime());
    }

    public function testFibStarting()
    {
        $wait = new FibonacciWaitStrategy(3, 2);
        $this->assertEquals(5, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(8, $wait->getWaitTime());
    }

    public function testReset()
    {
        $wait = new FibonacciWaitStrategy(3, 2);
        $wait->incrementWait();
        $wait->reset();
        $this->assertEquals(5, $wait->getWaitTime());
    }
}
