<?php
/**
 * File ExecutorFactory.php
 */

namespace Tebru\Executioner\Factory;

use Tebru\Executioner\Executor;
use Tebru\Executioner\Strategy\TerminationStrategy;
use Tebru\Executioner\Strategy\WaitStrategy;

/**
 * Class ExecutorFactory
 *
 * Creates ExecutorFactories
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ExecutorFactory
{
    /**
     * Make an ExecutorFactory
     *
     * @param WaitStrategy $waitStrategy
     * @param TerminationStrategy $terminationStrategy
     *
     * @return Executor
     */
    public function make(
        WaitStrategy $waitStrategy,
        TerminationStrategy $terminationStrategy
    ) {
        return new Executor($waitStrategy, $terminationStrategy);
    }
} 
