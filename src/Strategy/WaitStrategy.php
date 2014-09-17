<?php
/**
 * File WaitStrategy.php
 */

namespace Tebru\Executioner\Strategy;

/**
 * Interface WaitStrategy
 *
 * Use this interface to define a wait strategy during execution
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface WaitStrategy
{
    /**
     * Wait for a set number of seconds
     *
     * @return null
     */
    public function wait();

    /**
     * Get the number of seconds we should wait
     *
     * @return mixed
     */
    public function getWaitTime();

    /**
     * Increment wait time based on strategy
     *
     * @return mixed
     */
    public function incrementWait();

    /**
     * Reset this strategy
     *
     * @return null
     */
    public function reset();
}
