<?php
/**
 * File TimeBoundFactoryTest.php 
 */

namespace Tebru\Executioner\Test\Factory\TerminationStrategy;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Factory\TerminationStrategy\TimeBoundFactory;
use Tebru\Executioner\Strategy\Termination\TimeBoundStrategy;

/**
 * Class TimeBoundFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class TimeBoundFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testWillCreateTimeBoundStrategy()
    {
        $factory = new TimeBoundFactory();
        $strategy = $factory->make(0);
        $this->assertTrue(is_a($strategy, TimeBoundStrategy::class));
    }
}
