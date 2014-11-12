<?php
/**
 * File LogarithmicWaitStrategyFactory.php
 */

namespace Tebru\Executioner\Factory\Wait;

use Tebru\Executioner\Strategy\Wait\LogarithmicWaitStrategy;

/**
 * Class LogarithmicWaitStrategyFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class LogarithmicWaitStrategyFactory
{
    /**
     * Make a new LogarithmicWaitStrategy strategy
     *
     * @param int $startingWait
     * @param int $waitIncrement
     * @param int $logBase
     *
     * @return LogarithmicWaitStrategy
     */
    public function make(
        $startingWait = LogarithmicWaitStrategy::DEFAULT_STARTING_WAIT,
        $waitIncrement = LogarithmicWaitStrategy::DEFAULT_WAIT_INCREMENT,
        $logBase = LogarithmicWaitStrategy::DEFAULT_LOG_BASE
    ) {
        return new LogarithmicWaitStrategy($startingWait, $waitIncrement, $logBase);
    }
} 
