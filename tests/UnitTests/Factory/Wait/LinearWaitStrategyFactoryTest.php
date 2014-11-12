<?php
/**
 * File LinearWaitStrategyFactoryTest.php
 */

namespace Tebru\Executioner\Test\Factoy\WaitStrategy;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Factory\Wait\LinearWaitStrategyFactory;
use Tebru\Executioner\Strategy\Wait\LinearWaitStrategy;

/**
 * Class LinearWaitStrategyFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class LinearWaitStrategyFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testWillCreateLinearWaitStrategy()
    {
        $factory = new LinearWaitStrategyFactory();
        $strategy = $factory->make();
        $this->assertTrue(is_a($strategy, LinearWaitStrategy::class));
    }
}
