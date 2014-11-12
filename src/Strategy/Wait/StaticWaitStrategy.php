<?php
/**
 * File StaticWaitStrategy.php
 */

namespace Tebru\Executioner\Strategy\Wait;

use Tebru\Executioner\Strategy\Wait;

/**
 * Class StaticWaitStrategy
 *
 * Use this class to wait a set number of second each attempt
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class StaticWaitStrategy extends Wait
{
    /**#@+
     * Default values
     */
    const DEFAULT_SECONDS_PER_INTERVAL = 1;
    /**#@-*/

    /**
     * Number of microseconds to wait
     *
     * @var int $waitTime
     */
    private $waitTime;

    /**
     * Constructor
     *
     * @param int $secondsPerInterval Number of seconds to wait
     */
    public function __construct($secondsPerInterval = self::DEFAULT_SECONDS_PER_INTERVAL)
    {
        $this->waitTime = $secondsPerInterval;
    }

    /**
     * {@inheritdoc}
     */
    public function getWaitTime()
    {
        return $this->waitTime;
    }

    /**
     * {@inheritdoc}
     */
    public function incrementWait()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        return null;
    }
}
