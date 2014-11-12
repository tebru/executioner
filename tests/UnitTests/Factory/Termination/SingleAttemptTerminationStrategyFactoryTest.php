<?php
/**
 * File SingleAttemptTerminationStrategyFactoryTest.php
 */

namespace Tebru\Executioner\Test\Factory\TerminationStrategy;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Factory\Termination\SingleAttemptTerminationStrategyFactory;
use Tebru\Executioner\Strategy\Termination\SingleAttemptTerminationStrategy;

/**
 * Class SingleAttemptTerminationStrategyFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class SingleAttemptTerminationStrategyFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testWillCreateSingleAttemptStrategy()
    {
        $factory = new SingleAttemptTerminationStrategyFactory();
        $strategy = $factory->make();
        $this->assertTrue(is_a($strategy, SingleAttemptTerminationStrategy::class));
    }
}
