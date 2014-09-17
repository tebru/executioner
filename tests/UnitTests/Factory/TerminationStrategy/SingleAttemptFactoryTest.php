<?php
/**
 * File SingleAttemptFactoryTest.php 
 */

namespace Tebru\Executioner\Test\Factory\TerminationStrategy;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Factory\TerminationStrategy\SingleAttemptFactory;
use Tebru\Executioner\Strategy\Termination\SingleAttemptStrategy;

/**
 * Class SingleAttemptFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class SingleAttemptFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testWillCreateSingleAttemptStrategy()
    {
        $factory = new SingleAttemptFactory();
        $strategy = $factory->make();
        $this->assertTrue(is_a($strategy, SingleAttemptStrategy::class));
    }
}
