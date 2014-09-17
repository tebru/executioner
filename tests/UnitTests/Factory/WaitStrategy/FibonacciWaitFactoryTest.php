<?php
/**
 * File FibonacciWaitFactoryTest.php 
 */

namespace Tebru\Executioner\Test\Factoy\WaitStrategy;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Factory\WaitStrategy\FibonacciWaitFactory;
use Tebru\Executioner\Strategy\Wait\FibonacciWait;

/**
 * Class FibonacciWaitFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class FibonacciWaitFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testWillCreateFibonacciWaitStrategy()
    {
        $factory = new FibonacciWaitFactory();
        $strategy = $factory->make();
        $this->assertTrue(is_a($strategy, FibonacciWait::class));
    }
}
