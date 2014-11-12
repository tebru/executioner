<?php
/**
 * File TimeBoundTerminationStrategyFactoryTest.php
 */

namespace Tebru\Executioner\Test\Factory\TerminationStrategy;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Factory\Termination\TimeBoundTerminationStrategyFactory;
use Tebru\Executioner\Strategy\Termination\TimeBoundTerminationStrategy;

/**
 * Class TimeBoundTerminationStrategyFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class TimeBoundTerminationStrategyFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testWillCreateTimeBoundStrategy()
    {
        $factory = new TimeBoundTerminationStrategyFactory();
        $strategy = $factory->make(0);
        $this->assertTrue(is_a($strategy, TimeBoundTerminationStrategy::class));
    }
}
