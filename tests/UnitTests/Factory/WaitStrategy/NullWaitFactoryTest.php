<?php
/**
 * File NullWaitFactoryTest.php 
 */

namespace Tebru\Executioner\Test\Factoy\WaitStrategy;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Factory\WaitStrategy\NullWaitFactory;
use Tebru\Executioner\Strategy\Wait\NullWait;

/**
 * Class NullWaitFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class NullWaitFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testWillCreateNullWaitStrategy()
    {
        $factory = new NullWaitFactory();
        $strategy = $factory->make();
        $this->assertTrue(is_a($strategy, NullWait::class));
    }
}
