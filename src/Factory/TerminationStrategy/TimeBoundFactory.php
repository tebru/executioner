<?php
/**
 * File TimeBoundFactory.php 
 */

namespace Tebru\Executioner\Factory\TerminationStrategy;

use Tebru\Executioner\Strategy\Termination\TimeBoundStrategy;

/**
 * Class TimeBoundFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class TimeBoundFactory
{
    /**
     * Make a new TimeBoundStrategy
     *
     * @param $secondsEnding
     *
     * @return TimeBoundStrategy
     */
    public function make($secondsEnding)
    {
        return new TimeBoundStrategy($secondsEnding);
    }
} 
