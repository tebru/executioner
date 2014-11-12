<?php
/**
 * File TimeBoundTerminationStrategy.php
 */

namespace Tebru\Executioner\Strategy\Termination;

use Tebru\Executioner\Strategy\Termination;
use Tebru\Executioner\Strategy\TimeAwareTerminationStrategy;

/**
 * Class TimeBoundTerminationStrategy
 *
 * Use this strategy to stop execution based on a time limit
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class TimeBoundTerminationStrategy implements TimeAwareTerminationStrategy
{
    /**
     * The started time as a timestamp
     *
     * @var int $startedTime
     */
    private $startedTime;

    /**
     * The number of seconds we should attempt execution from when
     * start() is called
     *
     * @var int $secondsEnding
     */
    private $secondsEnding;

    /**
     * Timestamp of when we should end execution
     *
     * @var int $endingTime
     */
    private $endingTime;

    /**
     * Constructor
     *
     * @param int $secondsEnding
     */
    public function __construct($secondsEnding)
    {
        $this->secondsEnding = $secondsEnding;
    }

    /**
     * Inform the termination strategy we're starting the execution process
     *
     * @return null
     */
    public function reset()
    {
        $timeString = '+' . $this->secondsEnding . ' second';
        $this->startedTime = $this->getCurrentTime();
        $this->endingTime = strtotime($timeString, $this->getStartedTime());
    }

    /**
     * Returns true if the current time is past the ending time
     *
     * @return bool
     */
    public function hasFinished()
    {
        return $this->getCurrentTime() >= $this->endingTime;
    }

    /**
     * Get the started timestamp
     *
     * @return int
     */
    public function getStartedTime()
    {
        return $this->startedTime;
    }

    /**
     * Get the current timestamp
     *
     * @return int
     */
    public function getCurrentTime()
    {
        return time();
    }
}
