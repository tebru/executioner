<?php
/**
 * File TimeBoundStrategyTest.php
 */

namespace Tebru\Executioner\Test\Strategy\Termination;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Strategy\Termination\TimeBoundStrategy;

/**
 * Class TimeBoundStrategyTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class TimeBoundStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testZeroSecondsFinishesImmediately()
    {
        $strategy = new TimeBoundStrategy(0);
        $strategy->start();
        $this->assertTrue($strategy->hasFinished());
    }

    public function testOneSecondFinishIsFalse()
    {
        $strategy = new TimeBoundStrategy(1);
        $strategy->start();
        $this->assertFalse($strategy->hasFinished());
    }

    public function testReset()
    {
        $strategy = new TimeBoundStrategy(0);
        $strategy->start();
        $strategy->addAttempt();
        $strategy->reset();
        $this->assertSame(0, $strategy->getAttempts());
    }
}
