<?php
/**
 * File ExponentialWaitStrategyFactoryTest.php
 */

namespace Tebru\Executioner\Test\Factoy\WaitStrategy;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Factory\Wait\ExponentialWaitStrategyFactory;
use Tebru\Executioner\Strategy\Wait\ExponentialWaitStrategy;

/**
 * Class ExponentialWaitStrategyFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ExponentialWaitStrategyFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testWillCreateExponentialWaitStrategy()
    {
        $factory = new ExponentialWaitStrategyFactory();
        $strategy = $factory->make();
        $this->assertTrue(is_a($strategy, ExponentialWaitStrategy::class));
    }
}
