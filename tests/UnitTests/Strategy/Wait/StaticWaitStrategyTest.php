<?php
/**
 * File StaticWaitStrategyTest.php
 */

namespace Tebru\Executioner\Test\Strategy\Wait;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Strategy\Wait\StaticWaitStrategy;

/**
 * Class StaticWaitStrategyTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class StaticWaitStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testStaticWait()
    {
        $wait = new StaticWaitStrategy();
        $this->assertEquals(1, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(1, $wait->getWaitTime());
    }

    public function testStartingWait()
    {
        $wait = new StaticWaitStrategy(2);
        $this->assertEquals(2, $wait->getWaitTime());
        $wait->incrementWait();
        $this->assertEquals(2, $wait->getWaitTime());
    }

    public function testReset()
    {
        $wait = new StaticWaitStrategy(2);
        $wait->incrementWait();
        $wait->reset();
        $this->assertEquals(2, $wait->getWaitTime());
    }
} 
