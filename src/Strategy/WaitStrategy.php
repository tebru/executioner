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
     * Number of microseconds in one second
     */
    const MICROSECONDS_PER_SECOND = 1000000;

    /**
     * Sleep method
     */
    public function wait();
}
