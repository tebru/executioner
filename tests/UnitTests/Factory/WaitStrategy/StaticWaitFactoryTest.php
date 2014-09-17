<?php
/**
 * File StaticWaitFactoryTest.php 
 */

namespace Tebru\Executioner\Test\Factoy\WaitStrategy;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Factory\WaitStrategy\StaticWaitFactory;
use Tebru\Executioner\Strategy\Wait\StaticWait;

/**
 * Class StaticWaitFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class StaticWaitFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testWillCreateStaticWaitStrategy()
    {
        $factory = new StaticWaitFactory();
        $strategy = $factory->make();
        $this->assertTrue(is_a($strategy, StaticWait::class));
    }
}
