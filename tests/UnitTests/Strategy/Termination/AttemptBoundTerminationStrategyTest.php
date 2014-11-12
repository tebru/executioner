<?php
/**
 * File AttemptBoundTerminationStrategyTest.php
 */

namespace Tebru\Executioner\Test\Strategy\Termination;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Strategy\Termination\AttemptBoundTerminationStrategy;

/**
 * Class AttemptBoundTerminationStrategyTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class AttemptBoundTerminationStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultLimitOne()
    {
        $strategy = new AttemptBoundTerminationStrategy();
        $this->assertFalse($strategy->hasFinished());
        $strategy->addAttempt();
        $this->assertTrue($strategy->hasFinished());
    }

    public function testLimitTwo()
    {
        $strategy = new AttemptBoundTerminationStrategy(2);
        $this->assertFalse($strategy->hasFinished());
        $strategy->addAttempt();
        $this->assertFalse($strategy->hasFinished());
        $strategy->addAttempt();
        $this->assertTrue($strategy->hasFinished());
    }

    public function testAddAttempt()
    {
        $strategy = new AttemptBoundTerminationStrategy();
        $strategy->addAttempt();
        $this->assertSame(1, $strategy->getAttempts());
    }

    public function testReset()
    {
        $strategy = new AttemptBoundTerminationStrategy();
        $strategy->addAttempt();
        $strategy->reset();
        $this->assertSame(0, $strategy->getAttempts());
    }
}
