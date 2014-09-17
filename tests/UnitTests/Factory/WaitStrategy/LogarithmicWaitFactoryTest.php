<?php
/**
 * File LogarithmicWaitFactoryTest.php 
 */

namespace Tebru\Executioner\Test\Factoy\WaitStrategy;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Factory\WaitStrategy\LogarithmicWaitFactory;
use Tebru\Executioner\Strategy\Wait\LogarithmicWait;

/**
 * Class LogarithmicWaitFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class LogarithmicWaitFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testWillCreateLogarithmicWaitStrategy()
    {
        $factory = new LogarithmicWaitFactory();
        $strategy = $factory->make();
        $this->assertTrue(is_a($strategy, LogarithmicWait::class));
    }
}
