<?php
/**
 * File ExecutorFactoryTest.php
 */

namespace Tebru\Executioner\Test\Factory;
use Mockery;
use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Executor;
use Tebru\Executioner\Factory\ExecutorFactory;

/**
 * Class ExecutorFactoryTest
 *
 * @author Nate Brunette <nbrunett@nerdery.com>
 */
class ExecutorFactoryTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        Mockery::close();
    }

    public function testWillCreateExecutor()
    {
        $factory = new ExecutorFactory();
        $executor = $factory->make();
        $this->assertTrue(is_a($executor, Executor::class));
    }
} 
