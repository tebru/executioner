<?php
/**
 * File ExecutorFactory.php
 */

namespace Tebru\Executioner\Factory;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Tebru\Executioner\Executor;

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
     * @var EventDispatcher $eventDispatcher
     */
    private $eventDispatcher;

    /**
     * Constructor
     *
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(EventDispatcher $eventDispatcher = null)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Make an Executor
     *
     * @param EventDispatcher $eventDispatcher
     * @return Executor
     */
    public function make(EventDispatcher $eventDispatcher = null) {
        if (null !== $eventDispatcher) {
            return new Executor($eventDispatcher);
        }

        return new Executor($this->eventDispatcher);
    }
} 
