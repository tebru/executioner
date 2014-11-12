<?php
/**
 * File AttemptBoundTerminationStrategyFactoryTest.php
 */

namespace Tebru\Executioner\Test\Factory\TerminationStrategy;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Factory\Termination\AttemptBoundTerminationStrategyFactory;
use Tebru\Executioner\Strategy\Termination\AttemptBoundTerminationStrategy;

/**
 * Class AttemptBoundTerminationStrategyFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class AttemptBoundTerminationStrategyFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testWillCreateAttemptBoundStrategy()
    {
        $factory = new AttemptBoundTerminationStrategyFactory();
        $strategy = $factory->make();
        $this->assertTrue(is_a($strategy, AttemptBoundTerminationStrategy::class));
    }
}
