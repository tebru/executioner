<?php
/**
 * File LogarithmicWaitStrategy.php
 */

namespace Tebru\Executioner\Strategy\Wait;

use Tebru\Executioner\Strategy\Wait;

/**
 * Class LogarithmicWaitStrategy
 *
 * Use this class to wait based on a logarithmic curve
 *
 * @author Nate Brunette <n@tebru.net>
 */
class LogarithmicWaitStrategy extends Wait
{
    /**#@+
     * Default values
     */
    const DEFAULT_STARTING_WAIT = 2;
    const DEFAULT_WAIT_INCREMENT = 1;
    const DEFAULT_LOG_BASE = 10;
    /**#@-*/

    /**
     * The wait time we start with
     *
     * @var  $startingWait
     */
    private $startingWait;

    /**
     * The base number in seconds used for calculating wait time
     *
     * @var int $baseWait
     */
    private $baseWait;

    /**
     * The amount we should increment each attempt
     *
     * @var int waitIncrement
     */
    private $baseIncrement;

    /**
     * Which number base we should use
     *
     * @var int logBase
     */
    private $logBase;

    /**
     * Constructor
     *
     * @param int $startingWait Number of seconds we should start wait with
     * @param int $waitIncrement Number of seconds we should increment base by
     * @param int $logBase Number base
     */
    public function __construct(
        $startingWait = self::DEFAULT_STARTING_WAIT,
        $waitIncrement = self::DEFAULT_WAIT_INCREMENT,
        $logBase = self::DEFAULT_LOG_BASE
    ) {
        $this->startingWait = $startingWait;
        $this->baseWait = $startingWait;
        $this->baseIncrement = $waitIncrement;
        $this->logBase = $logBase;
    }

    /**
     * {@inheritdoc}
     */
    public function getWaitTime()
    {
        return log($this->baseWait, $this->logBase);
    }

    /**
     * {@inheritdoc}
     */
    public function incrementWait()
    {
        $this->baseWait += $this->baseIncrement;
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->baseWait = $this->startingWait;
    }

}
