<?php
/**
 * File ExponentialWait.php
 */

namespace Tebru\Executioner\Strategy\Wait;

use Tebru\Executioner\Strategy\Wait;

/**
 * Class ExponentialWait
 *
 * Use this strategy to have wait time increase exponentially
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class ExponentialWait extends Wait
{
    /**#@+
     * Default values
     */
    const DEFAULT_EXPONENT = 2;
    const DEFAULT_STARTING_WAIT = 1;
    const DEFAULT_WAIT_INCREMENT = 1;
    /**#@-*/

    /**
     * The exponent used to increase time
     *
     * @var int $exponent
     */
    private $exponent;

    /**
     * The number of seconds we're adding a power to
     *
     * @var int $baseWait
     */
    private $baseWait;

    /**
     * How much we should increase the $baseWait by each iteration
     *
     * @var int intervalIncrement
     */
    private $baseIncrement;

    /**
     * Constructor
     *
     * @param int $exponent
     * @param int $startingWait How many seconds we should start the wait with
     * @param int $waitIncrement How much we should increase the base number by
     */
    public function __construct(
        $exponent = self::DEFAULT_EXPONENT,
        $startingWait = self::DEFAULT_STARTING_WAIT,
        $waitIncrement = self::DEFAULT_WAIT_INCREMENT
    ) {
        $this->exponent = $exponent;
        $this->baseWait = $startingWait;
        $this->baseIncrement = $waitIncrement;
    }

    /**
     * {@inheritdoc}
     */
    public function getWaitTime()
    {
        return pow($this->baseWait, $this->exponent);
    }

    /**
     * {@inheritdoc}
     */
    public function incrementWait()
    {
        $this->baseWait += $this->baseIncrement;
    }
}
