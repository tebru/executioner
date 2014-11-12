<?php
/**
 * File TimeBoundTerminationStrategyTest.php
 */

namespace Tebru\Executioner\Test\Strategy\Termination;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Strategy\Termination\TimeBoundTerminationStrategy;

/**
 * Class TimeBoundTerminationStrategyTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class TimeBoundTerminationStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testZeroSecondsFinishesImmediately()
    {
        $strategy = new TimeBoundTerminationStrategy(0);
        $strategy->reset();
        $this->assertTrue($strategy->hasFinished());
    }

    public function testOneSecondFinishIsFalse()
    {
        $strategy = new TimeBoundTerminationStrategy(1);
        $strategy->reset();
        $this->assertFalse($strategy->hasFinished());
    }
}
