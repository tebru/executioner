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
     * If adding a logger, both name and instance must be passed in.  If adding a wait, it must
     * be an int to represent seconds, or a WaitStrategy
     *
     * @param null $loggerName
     * @param LoggerInterface $logger
     * @param int|WaitStrategy $wait WaitStrategy or wait time in seconds
     * @param EventDispatcherInterface $eventDispatcher
     * @return Executor
     */
    public static function make(
        $loggerName = null,
        LoggerInterface $logger = null,
        $wait = null,
        EventDispatcherInterface $eventDispatcher = null
    ) {
        $executor = new Executor();

        if (!is_null($loggerName) xor !is_null($logger)) {
            throw new InvalidArgumentException('Logger name and logger must both be set');
        }

        if (!is_null($wait) && !is_int($wait) && !$wait instanceof WaitStrategy) {
            throw new InvalidArgumentException('Wait must be an int or an instance of WaitStrategy');
        }

        if (!is_null($loggerName) && !is_null($logger)) {
            $executor->setLogger($loggerName, $logger);
        }

        if (is_int($wait)) {
            $executor->setWait($wait);
        }

        if ($wait instanceof WaitStrategy) {
            $executor->setWaitStrategy($wait);
        }

        if (null !== $eventDispatcher) {
            $executor->setDispatcher($eventDispatcher);
        }

        return $executor;
    }
} 
