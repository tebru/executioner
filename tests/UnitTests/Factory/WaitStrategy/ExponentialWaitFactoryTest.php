<?php
/**
 * File ExponentialWaitFactoryTest.php 
 */

namespace Tebru\Executioner\Test\Factoy\WaitStrategy;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Factory\WaitStrategy\ExponentialWaitFactory;
use Tebru\Executioner\Strategy\Wait\ExponentialWait;

/**
 * Class ExponentialWaitFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ExponentialWaitFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testWillCreateExponentialWaitStrategy()
    {
        $factory = new ExponentialWaitFactory();
        $strategy = $factory->make();
        $this->assertTrue(is_a($strategy, ExponentialWait::class));
    }
}
