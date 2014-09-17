<?php
/**
 * File AttemptBoundFactoryTest.php 
 */

namespace Tebru\Executioner\Test\Factory\TerminationStrategy;

use PHPUnit_Framework_TestCase;
use Tebru\Executioner\Factory\TerminationStrategy\AttemptBoundFactory;
use Tebru\Executioner\Strategy\Termination\AttemptBoundStrategy;

/**
 * Class AttemptBoundFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class AttemptBoundFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testWillCreateAttemptBoundStrategy()
    {
        $factory = new AttemptBoundFactory();
        $strategy = $factory->make();
        $this->assertTrue(is_a($strategy, AttemptBoundStrategy::class));
    }
}
