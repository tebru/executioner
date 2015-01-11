<?php
/**
 * File ExponentialBackoffStrategy.php 
 */

namespace Tebru\Executioner\Strategy;

/**
 * Class ExponentialBackoffStrategy
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ExponentialBackoffStrategy implements WaitStrategy
{
    /**
     * Current number of attempts
     *
     * @var int $attempts
     */
    private $attempts = 0;

    /**
     * Wait time in seconds
     *
     * @var int $waitPerSlot
     */
    private $waitPerSlot;

    /**
     * Maximum amount of a time we should wait
     *
     * @var int $maxWait
     */
    private $maxWait;

    /**
     * Constructor
     *
     * @param int $waitPerSlot
     * @param int $maxWait 10 minutes by default
     */
    public function __construct($waitPerSlot = 1, $maxWait = 600)
    {
        $this->waitPerSlot = $waitPerSlot;
        $this->maxWait = $maxWait;
    }

    /**
     * Sleep based on exponential backoff algorithm
     *
     * random number between 0 and 2^attempts - 1, then multiplied by the slot wait time
     */
    public function wait()
    {
        // 2^attempts
        $upper = (1 << ++$this->attempts) - 1;

        // random(0, $upper) * slot wait
        $waitTime = mt_rand(0, $upper) * $this->waitPerSlot;

        if ($waitTime > $this->maxWait) {
            $waitTime = $this->maxWait;
        }

        usleep($waitTime * WaitStrategy::MICROSECONDS_PER_SECOND);
    }
}
