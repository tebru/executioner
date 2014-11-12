<?php
/**
 * File TimeBoundTerminationStrategyFactory.php
 */

namespace Tebru\Executioner\Factory\Termination;

use Tebru\Executioner\Strategy\Termination\TimeBoundTerminationStrategy;

/**
 * Class TimeBoundTerminationStrategyFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class TimeBoundTerminationStrategyFactory
{
    /**
     * Make a new TimeBoundTerminationStrategy
     *
     * @param $secondsEnding
     *
     * @return TimeBoundTerminationStrategy
     */
    public function make($secondsEnding)
    {
        return new TimeBoundTerminationStrategy($secondsEnding);
    }
} 
