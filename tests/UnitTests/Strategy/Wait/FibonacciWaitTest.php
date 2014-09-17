<?php
/**
 * File FibonacciWaitTest.php
 */

namespace Tebru\Executioner\Test\Strategy\Wait;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Strategy\Wait\FibonacciWait;

/**
 * Class FibonacciWaitTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class FibonacciWaitTest extends PHPUnit_Framework_TestCase
{
    public function testFibDefaults()
    {
        $wait = new FibonacciWait();
        $this->assertEquals(1, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(2, $wait->getWaitTime());
    }

    public function testFibStarting()
    {
        $wait = new FibonacciWait(3, 2);
        $this->assertEquals(5, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(8, $wait->getWaitTime());
    }

    public function testReset()
    {
        $wait = new FibonacciWait(3, 2);
        $wait->incrementWait();
        $wait->reset();
        $this->assertEquals(5, $wait->getWaitTime());
    }
}
