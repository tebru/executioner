<?php
/**
 * File NullWaitStrategyFactoryTest.php
 */

namespace Tebru\Executioner\Test\Factoy\WaitStrategy;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Factory\Wait\NullWaitStrategyFactory;
use Tebru\Executioner\Strategy\Wait\NullWaitStrategy;

/**
 * Class NullWaitStrategyFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class NullWaitStrategyFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testWillCreateNullWaitStrategy()
    {
        $factory = new NullWaitStrategyFactory();
        $strategy = $factory->make();
        $this->assertTrue(is_a($strategy, NullWaitStrategy::class));
    }
}
