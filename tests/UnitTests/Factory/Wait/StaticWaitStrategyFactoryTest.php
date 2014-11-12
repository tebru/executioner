<?php
/**
 * File StaticWaitStrategyFactoryTest.php
 */

namespace Tebru\Executioner\Test\Factoy\WaitStrategy;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Factory\Wait\StaticWaitStrategyFactory;
use Tebru\Executioner\Strategy\Wait\StaticWaitStrategy;

/**
 * Class StaticWaitStrategyFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class StaticWaitStrategyFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testWillCreateStaticWaitStrategy()
    {
        $factory = new StaticWaitStrategyFactory();
        $strategy = $factory->make();
        $this->assertTrue(is_a($strategy, StaticWaitStrategy::class));
    }
}
