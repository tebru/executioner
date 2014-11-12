<?php
/**
 * File SingleAttemptTerminationStrategyTest.php
 */

namespace Tebru\Executioner\Test\Strategy\Termination;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Strategy\Termination\SingleAttemptTerminationStrategy;

/**
 * Class SingleAttemptTerminationStrategyTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class SingleAttemptTerminationStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testIsAlwaysFinished()
    {
        $strategy = new SingleAttemptTerminationStrategy();
        $this->assertTrue($strategy->hasFinished());
    }
}
