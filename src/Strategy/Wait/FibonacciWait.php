<?php
/**
 * File FibonacciWait.php
 */

namespace Tebru\Executioner\Strategy\Wait;

use Tebru\Executioner\Strategy\Wait;

/**
 * Class FibonacciWait
 *
 * Use this class to wait based on the fibonacci sequence
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class FibonacciWait extends Wait
{
    /**#@+
     * Default values
     */
    const DEFAULT_STARTING_FIB = 1;
    const DEFAULT_PREVIOUS_FIB = 0;
    /**#@-*/

    /**
     * Current fibonacci number
     *
     * @var int $startingFibNumber
     */
    private $currentFibNumber;
    /**
     * Previous fibonacci number
     *
     * @var int previousFibNumber
     */
    private $previousFibNumber;

    /**
     * Constructor
     *
     * @param int $startingFibNumber
     * @param int $previousFibNumber
     */
    public function __construct(
        $startingFibNumber = self::DEFAULT_STARTING_FIB,
        $previousFibNumber = self::DEFAULT_PREVIOUS_FIB
    ) {
        $this->currentFibNumber = $startingFibNumber;
        $this->previousFibNumber = $previousFibNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function getWaitTime()
    {
        return $this->currentFibNumber + $this->previousFibNumber;
    }

    public function incrementWait()
    {
        $next = $this->currentFibNumber + $this->previousFibNumber;
        $this->previousFibNumber = $this->currentFibNumber;
        $this->currentFibNumber = $next;
    }
}
