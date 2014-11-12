<?php
/**
 * File LinearWaitStrategyFactory.php
 */

namespace Tebru\Executioner\Factory\Wait;

use Tebru\Executioner\Strategy\Wait\LinearWaitStrategy;

/**
 * Class LinearWaitStrategyFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class LinearWaitStrategyFactory
{
    /**
     * Make a new LinearWaitStrategy strategy
     *
     * @param int $startingWait
     * @param int $waitIncrement
     *
     * @return LinearWaitStrategy
     */
    public function make(
        $startingWait = LinearWaitStrategy::DEFAULT_STARTING_WAIT,
        $waitIncrement = LinearWaitStrategy::DEFAULT_WAIT_INCREMENT
    ) {
        return new LinearWaitStrategy($startingWait, $waitIncrement);
    }
} 
