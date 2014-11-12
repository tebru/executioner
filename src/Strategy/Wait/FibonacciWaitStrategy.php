<?php
/**
 * File FibonacciWaitStrategy.php
 */

namespace Tebru\Executioner\Strategy\Wait;

use Tebru\Executioner\Strategy\Wait;

/**
 * Class FibonacciWaitStrategy
 *
 * Use this class to wait based on the fibonacci sequence
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class FibonacciWaitStrategy extends Wait
{
    /**#@+
     * Default values
     */
    const DEFAULT_STARTING_FIB = 1;
    const DEFAULT_PREVIOUS_FIB = 0;
    /**#@-*/

    /**
     * The starting current fibonacci number
     *
     * @var  $startingFib
     */
    private $startingFib;

    /**
     * The starting previous fibonacci number
     *
     * @var  $startingPrevFib
     */
    private $startingPrevFib;

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
        $this->startingFib = $startingFibNumber;
        $this->startingPrevFib = $previousFibNumber;
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

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->currentFibNumber = $this->startingFib;
        $this->previousFibNumber = $this->startingPrevFib;
    }

}
