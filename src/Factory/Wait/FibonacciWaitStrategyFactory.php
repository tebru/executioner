<?php
/**
 * File FibonacciWaitStrategyFactory.php
 */

namespace Tebru\Executioner\Factory\Wait;

use Tebru\Executioner\Strategy\Wait\FibonacciWaitStrategy;

/**
 * Class FibonacciWaitStrategyFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class FibonacciWaitStrategyFactory
{
    /**
     * Make a new FibonacciWaitStrategy strategy
     *
     * @param int $startingFibNumber
     * @param int $previousFibNumber
     *
     * @return FibonacciWaitStrategy
     */
    public function make(
        $startingFibNumber = FibonacciWaitStrategy::DEFAULT_STARTING_FIB,
        $previousFibNumber = FibonacciWaitStrategy::DEFAULT_PREVIOUS_FIB
    ) {
        return new FibonacciWaitStrategy($startingFibNumber, $previousFibNumber);
    }
} 
