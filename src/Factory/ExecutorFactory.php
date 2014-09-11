<?php
/**
 * File ExecutorFactory.php
 */

namespace Tebru\Executioner\Factory;

use Tebru\Executioner\Attemptor;
use Tebru\Executioner\Executor;
use Tebru\Executioner\Strategy\TerminationStrategy;
use Tebru\Executioner\Strategy\WaitStrategy;
use Tebru\Logger\ExceptionLogger;

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
     * @param Attemptor $attemptor
     * @param ExceptionLogger $logger
     * @param WaitStrategy $waitStrategy
     * @param TerminationStrategy $terminationStrategy
     *
     * @return Executor
     */
    public function make(
        Attemptor $attemptor,
        ExceptionLogger $logger,
        WaitStrategy $waitStrategy,
        TerminationStrategy $terminationStrategy
    ) {
        return new Executor($attemptor, $logger, $waitStrategy, $terminationStrategy);
    }
} 
