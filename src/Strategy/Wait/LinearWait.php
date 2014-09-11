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
    public function __construct($startingWait = 1, $waitIncrement = 1)
    {
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
}
