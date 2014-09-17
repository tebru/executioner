<?php
/**
 * File TimeBoundStrategy.php
 */

namespace Tebru\Executioner\Strategy\Termination;

use Tebru\Executioner\Strategy\Termination;

/**
 * Class TimeBoundStrategy
 *
 * Use this strategy to stop execution based on a time limit
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class TimeBoundStrategy extends Termination
{
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
     * Returns true if the current time is past the ending time
     *
     * @return bool
     */
    public function hasFinished()
    {
        return $this->getCurrentTime() >= $this->endingTime;
    }

    /**
     * After we've called start(), set the ending time from the started time
     */
    protected function started()
    {
        $timeString = '+' . $this->secondsEnding . ' second';
        $this->endingTime = strtotime($timeString, $this->getStartedTime());
    }

    /**
     * Reset this strategy
     *
     * Time gets reset on consecutive calls to start()
     *
     * @return null
     */
    public function reset()
    {
        $this->setAttempts(0);
    }
}
