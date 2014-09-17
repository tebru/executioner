<?php
/**
 * File LinearWaitFactoryTest.php 
 */

namespace Tebru\Executioner\Test\Factoy\WaitStrategy;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Factory\WaitStrategy\LinearWaitFactory;
use Tebru\Executioner\Strategy\Wait\LinearWait;

/**
 * Class LinearWaitFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class LinearWaitFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testWillCreateLinearWaitStrategy()
    {
        $factory = new LinearWaitFactory();
        $strategy = $factory->make();
        $this->assertTrue(is_a($strategy, LinearWait::class));
    }
}
