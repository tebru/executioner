<?php
/**
 * File LogarithmicWaitFactory.php 
 */

namespace Tebru\Executioner\Factory\WaitStrategy;

use Tebru\Executioner\Strategy\Wait\LogarithmicWait;

/**
 * Class LogarithmicWaitFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class LogarithmicWaitFactory
{
    /**
     * Make a new LogarithmicWait strategy
     *
     * @param int $startingWait
     * @param int $waitIncrement
     * @param int $logBase
     *
     * @return LogarithmicWait
     */
    public function make(
        $startingWait = LogarithmicWait::DEFAULT_STARTING_WAIT,
        $waitIncrement = LogarithmicWait::DEFAULT_WAIT_INCREMENT,
        $logBase = LogarithmicWait::DEFAULT_LOG_BASE
    ) {
        return new LogarithmicWait($startingWait, $waitIncrement, $logBase);
    }
} 
