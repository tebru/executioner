<?php
/**
 * File AttemptBoundStrategyTest.php
 */

namespace Tebru\Executioner\Test\Strategy\Termination;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Strategy\Termination\AttemptBoundStrategy;

/**
 * Class AttemptBoundStrategyTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class AttemptBoundStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultLimitOne()
    {
        $strategy = new AttemptBoundStrategy();
        $this->assertFalse($strategy->hasFinished());
        $strategy->addAttempt();
        $this->assertTrue($strategy->hasFinished());
    }

    public function testLimitTwo()
    {
        $strategy = new AttemptBoundStrategy(2);
        $this->assertFalse($strategy->hasFinished());
        $strategy->addAttempt();
        $this->assertFalse($strategy->hasFinished());
        $strategy->addAttempt();
        $this->assertTrue($strategy->hasFinished());
    }

    public function testAddAttempt()
    {
        $strategy = new AttemptBoundStrategy();
        $strategy->addAttempt();
        $this->assertSame(1, $strategy->getAttempts());
    }
}
