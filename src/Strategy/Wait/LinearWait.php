<?php
/**
 * File LinearWait.php
 */

namespace Tebru\Executioner\Strategy\Wait;

use Tebru\Executioner\Strategy\Wait;

/**
 * Class LinearWait
 *
 * Use this class to wait based on linear time
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class LinearWait extends Wait
{
    /**#@+
     * Default values
     */
    const DEFAULT_STARTING_WAIT = 1;
    const DEFAULT_WAIT_INCREMENT = 1;
    /**#@-*/

    /**
     * The wait time we start with
     *
     * @var  $startingWait
     */
    private $startingWait;

    /**
     * The amount we should increment each attempt
     *
     * @var int $baseIncrement
     */
    private $baseIncrement;

    /**
     * The base number in seconds used for calculating wait time
     *
     * @var int $baseWait
     */
    private $baseWait = 1;

    /**
     * Constructor
     *
     * @param int $startingWait Number of seconds we should start waiting at
     * @param int $waitIncrement Number of seconds we should add to wait time
     */
    public function __construct(
        $startingWait = self::DEFAULT_STARTING_WAIT,
        $waitIncrement = self::DEFAULT_WAIT_INCREMENT
    ) {
        $this->startingWait = $startingWait;
        $this->baseWait = $startingWait;
        $this->baseIncrement = $waitIncrement;
    }

    /**
     * {@inheritdoc}
     */
    public function getWaitTime()
    {
        return $this->baseWait;
    }

    /**
     * {@inheritdoc}
     */
    public function incrementWait()
    {
        $this->baseWait += $this->baseIncrement;
    }

    /**
     * Reset this strategy
     *
     * @return null
     */
    public function reset()
    {
        $this->baseWait = $this->startingWait;
    }
}
