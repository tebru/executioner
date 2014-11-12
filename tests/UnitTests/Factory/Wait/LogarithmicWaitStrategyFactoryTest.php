<?php
/**
 * File LogarithmicWaitStrategyFactoryTest.php
 */

namespace Tebru\Executioner\Test\Factoy\WaitStrategy;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Factory\Wait\LogarithmicWaitStrategyFactory;
use Tebru\Executioner\Strategy\Wait\LogarithmicWaitStrategy;

/**
 * Class LogarithmicWaitStrategyFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class LogarithmicWaitStrategyFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testWillCreateLogarithmicWaitStrategy()
    {
        $factory = new LogarithmicWaitStrategyFactory();
        $strategy = $factory->make();
        $this->assertTrue(is_a($strategy, LogarithmicWaitStrategy::class));
    }
}
