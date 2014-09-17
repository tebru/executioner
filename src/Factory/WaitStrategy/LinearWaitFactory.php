<?php
/**
 * File LinearWaitFactory.php 
 */

namespace Tebru\Executioner\Factory\WaitStrategy;

use Tebru\Executioner\Strategy\Wait\LinearWait;

/**
 * Class LinearWaitFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class LinearWaitFactory
{
    /**
     * Make a new LinearWait strategy
     *
     * @param int $startingWait
     * @param int $waitIncrement
     *
     * @return LinearWait
     */
    public function make(
        $startingWait = LinearWait::DEFAULT_STARTING_WAIT,
        $waitIncrement = LinearWait::DEFAULT_WAIT_INCREMENT
    ) {
        return new LinearWait($startingWait, $waitIncrement);
    }
} 
