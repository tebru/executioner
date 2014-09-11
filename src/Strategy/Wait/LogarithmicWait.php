<?php
/**
 * File LogarithmicWait.php
 */

namespace Tebru\Executioner\Strategy\Wait;

use Tebru\Executioner\Strategy\Wait;

/**
 * Class LogarithmicWait
 *
 * Use this class to wait based on a logarithmic curve
 *
 * @author Nate Brunette <n@tebru.net>
 */
class LogarithmicWait extends Wait
{
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
    public function __construct($startingWait = 2, $waitIncrement = 1, $logBase = 10)
    {
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
}
