<?php
/**
 * File SingleAttemptStrategyTest.php
 */

namespace Tebru\Executioner\Test\Strategy\Termination;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Strategy\Termination\SingleAttemptStrategy;

/**
 * Class SingleAttemptStrategyTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class SingleAttemptStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testIsAlwaysFinished()
    {
        $strategy = new SingleAttemptStrategy();
        $this->assertTrue($strategy->hasFinished());
    }
}
