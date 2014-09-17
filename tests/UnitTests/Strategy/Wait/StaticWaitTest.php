<?php
/**
 * File StaticWaitTest.php
 */

namespace Tebru\Executioner\Test\Strategy\Wait;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Strategy\Wait\StaticWait;

/**
 * Class StaticWaitTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class StaticWaitTest extends PHPUnit_Framework_TestCase
{
    public function testStaticWait()
    {
        $wait = new StaticWait();
        $this->assertEquals(1, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(1, $wait->getWaitTime());
    }

    public function testStartingWait()
    {
        $wait = new StaticWait(2);
        $this->assertEquals(2, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(2, $wait->getWaitTime());
    }

    public function testReset()
    {
        $wait = new StaticWait(2);
        $wait->incrementWait();
        $wait->reset();
        $this->assertEquals(2, $wait->getWaitTime());
    }
} 
