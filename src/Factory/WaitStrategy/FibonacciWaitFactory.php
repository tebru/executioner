<?php
/**
 * File FibonacciWaitFactory.php 
 */

namespace Tebru\Executioner\Factory\WaitStrategy;

use Tebru\Executioner\Strategy\Wait\FibonacciWait;

/**
 * Class FibonacciWaitFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class FibonacciWaitFactory
{
    /**
     * Make a new FibonacciWait strategy
     *
     * @param int $startingFibNumber
     * @param int $previousFibNumber
     *
     * @return FibonacciWait
     */
    public function make(
        $startingFibNumber = FibonacciWait::DEFAULT_STARTING_FIB,
        $previousFibNumber = FibonacciWait::DEFAULT_PREVIOUS_FIB
    ) {
        return new FibonacciWait($startingFibNumber, $previousFibNumber);
    }
} 
