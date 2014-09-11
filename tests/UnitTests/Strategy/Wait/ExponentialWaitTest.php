<?php
/**
 * File ExponentialWaitTest.php
 */

namespace Tebru\Executioner\Test\Strategy\Wait;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Strategy\Wait\ExponentialWait;

/**
 * Class ExponentialWaitTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ExponentialWaitTest extends PHPUnit_Framework_TestCase
{
    public function testExponentialWait()
    {
        $wait = new ExponentialWait(2);
        $this->assertEquals(1, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(4, $wait->getWaitTime());
    }

    public function testExponenetialWaitZeroExponent()
    {
        $wait = new ExponentialWait(0);
        $this->assertEquals(1, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(1, $wait->getWaitTime());
    }

    public function testExponenetialWaitOneExponent()
    {
        $wait = new ExponentialWait(1);
        $this->assertEquals(1, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(2, $wait->getWaitTime());
    }

    public function testStartingWait()
    {
        $wait = new ExponentialWait(2, 2);
        $this->assertEquals(4, $wait->getWaitTime());
    }

    public function testIncrement()
    {
        $wait = new ExponentialWait(2, 1, 2);
        $this->assertEquals(1, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(9, $wait->getWaitTime());
    }
}
