<?php
/**
 * File StaticWait.php
 */

namespace Tebru\Executioner\Strategy\Wait;

use Tebru\Executioner\Strategy\Wait;

/**
 * Class StaticWait
 *
 * Use this class to wait a set number of second each attempt
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class StaticWait extends Wait
{
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
    public function __construct($secondsPerInterval = 1)
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
}
