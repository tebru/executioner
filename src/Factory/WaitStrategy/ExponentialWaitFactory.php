<?php
/**
 * File ExponentialWaitFactory.php
 */

namespace Tebru\Executioner\Factory\WaitStrategy;

use Tebru\Executioner\Strategy\Wait\ExponentialWait;

/**
 * Class ExponentialWaitFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ExponentialWaitFactory
{
    /**
     * Make a new ExponentialWait
     *
     * @param int $exponent
     * @param int $startingWait
     * @param int $waitIncrement
     *
     * @return ExponentialWait
     */
    public function make(
        $exponent = ExponentialWait::DEFAULT_EXPONENT,
        $startingWait = ExponentialWait::DEFAULT_STARTING_WAIT,
        $waitIncrement = ExponentialWait::DEFAULT_WAIT_INCREMENT
    ) {
        return new ExponentialWait($exponent, $startingWait, $waitIncrement);
    }
} 
