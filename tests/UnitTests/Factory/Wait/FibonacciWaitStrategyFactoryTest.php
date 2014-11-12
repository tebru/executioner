<?php
/**
 * File FibonacciWaitStrategyFactoryTest.php
 */

namespace Tebru\Executioner\Test\Factoy\WaitStrategy;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Factory\Wait\FibonacciWaitStrategyFactory;
use Tebru\Executioner\Strategy\Wait\FibonacciWaitStrategy;

/**
 * Class FibonacciWaitStrategyFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class FibonacciWaitStrategyFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testWillCreateFibonacciWaitStrategy()
    {
        $factory = new FibonacciWaitStrategyFactory();
        $strategy = $factory->make();
        $this->assertTrue(is_a($strategy, FibonacciWaitStrategy::class));
    }
}
