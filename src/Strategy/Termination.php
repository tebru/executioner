<?php
/**
 * File Termination.php
 */

namespace Tebru\Executioner\Strategy;

/**
 * Class Termination
 *
 * @author Nate Brunette <n@tebru.net>
 */
abstract class Termination implements TerminationStrategy
{
    /**
     * The number of attempts made
     *
     * @var int $attempts
     */
    private $attempts = 0;

    /**
     * A timestamp of when we started attempts
     *
     * @var int $startedTime
     */
    private $startedTime;

    /**
     * {@inheritdoc}
     */
    final public function start()
    {
        if (null !== $this->startedTime) {
            return null;
        }

        $this->startedTime = $this->getCurrentTime();

        $this->started();
    }

    /**
     * Called after start() is called
     */
    protected function started() {}

    /**
     * {@inheritdoc}
     */
    final public function getStartedTime()
    {
        return $this->startedTime;
    }

    /**
     * {@inheritdoc}
     */
    final public function addAttempt()
    {
        ++$this->attempts;

        $this->addedAttempt();
    }

    /**
     * Called after addAttempt() is called
     */
    protected function addedAttempt() {}

    /**
     * {@inheritdoc}
     */
    final public function getAttempts()
    {
        return $this->attempts;
    }

    /**
     * Returns the current time from time()
     *
     * @return int
     */
    final protected function getCurrentTime()
    {
        return time();
    }
}
