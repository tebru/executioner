<?php
/**
 * File ExecutorFactory.php
 */

namespace Tebru\Executioner\Factory;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tebru\Executioner\Exception\InvalidArgumentException;
use Tebru\Executioner\Executor;
use Tebru\Executioner\Strategy\WaitStrategy;

/**
 * Class ExecutorFactory
 *
 * Creates an Executor
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ExecutorFactory
{
    /**
     * Make an Executor
     *
     * If adding a logger, both name and instance must be passed in.  If adding a wait, only
     * time or the strategy may be passed in.
     *
     * @param null $loggerName
     * @param LoggerInterface $logger
     * @param int $waitTime
     * @param WaitStrategy $waitStrategy
     * @param EventDispatcherInterface $eventDispatcher
     * @return Executor
     */
    public function make(
        $loggerName = null,
        LoggerInterface $logger = null,
        $waitTime = null,
        WaitStrategy $waitStrategy = null,
        EventDispatcherInterface $eventDispatcher = null
    ) {
        $executor = new Executor($eventDispatcher);

        if (!is_null($loggerName) xor !is_null($logger)) {
            throw new InvalidArgumentException('Logger name and logger must both be set');
        }

        if (!is_null($waitTime) && !is_null($waitStrategy)) {
            throw new InvalidArgumentException('Wait time and wait strategy cannot both be set');
        }

        if (!is_null($loggerName) && !is_null($logger)) {
            $executor->addLogger($loggerName, $logger);
        }

        if (is_null($waitTime) && is_null($waitStrategy)) {
            return $executor;
        }

        if (null !== $waitTime) {
            $executor->addWait($waitTime);
        }

        if (null !== $waitStrategy) {
            $executor->addWaitStrategy($waitStrategy);
        }

        return $executor;
    }
} 
