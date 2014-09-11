<?php
/**
 * File LinearWaitTest.php
 */

namespace Tebru\Executioner\Test\Strategy\Wait;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Strategy\Wait\LinearWait;

/**
 * Class LinearWaitTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class LinearWaitTest extends PHPUnit_Framework_TestCase
{
    public function testLinearWait()
    {
        $wait = new LinearWait();
        $this->assertEquals(1, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(2, $wait->getWaitTime());
    }

    public function testStartingWait()
    {
        $wait = new LinearWait(2);
        $this->assertEquals(2, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(3, $wait->getWaitTime());
    }

    public function testIncrement()
    {
        $wait = new LinearWait(1, 2);
        $this->assertEquals(1, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(3, $wait->getWaitTime());
    }
}
