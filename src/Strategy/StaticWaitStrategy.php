<?php
/**
 * File StaticWaitStrategy.php 
 */

namespace Tebru\Executioner\Strategy;

/**
 * Class StaticWaitStrategy
 *
 * @author Nate Brunette <n@tebru.net>
 */
class StaticWaitStrategy implements WaitStrategy
{
    /**
     * Number of seconds to sleep
     *
     * @var int $waitLength
     */
    private $waitLength;

    /**
     * Constructor
     *
     * @param int $waitLength
     */
    public function __construct($waitLength = 1)
    {
        $this->waitLength = $waitLength;
    }

    /**
     * Sleep for $this->waitLength seconds
     */
    public function wait()
    {
        usleep($this->waitLength * WaitStrategy::MICROSECONDS_PER_SECOND);
    }
}
