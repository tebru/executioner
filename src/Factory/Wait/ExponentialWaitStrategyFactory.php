<?php
/**
 * File ExponentialWaitStrategyFactory.php
 */

namespace Tebru\Executioner\Factory\Wait;

use Tebru\Executioner\Strategy\Wait\ExponentialWaitStrategy;

/**
 * Class ExponentialWaitStrategyFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ExponentialWaitStrategyFactory
{
    /**
     * Make a new ExponentialWaitStrategy
     *
     * @param int $exponent
     * @param int $startingWait
     * @param int $waitIncrement
     *
     * @return ExponentialWaitStrategy
     */
    public function make(
        $exponent = ExponentialWaitStrategy::DEFAULT_EXPONENT,
        $startingWait = ExponentialWaitStrategy::DEFAULT_STARTING_WAIT,
        $waitIncrement = ExponentialWaitStrategy::DEFAULT_WAIT_INCREMENT
    ) {
        return new ExponentialWaitStrategy($exponent, $startingWait, $waitIncrement);
    }
} 
