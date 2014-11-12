<?php
/**
 * File TimeAwareTerminationStrategy.php 
 */

namespace Tebru\Executioner\Strategy;

/**
 * Interface TimeAwareTerminationStrategy
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface TimeAwareTerminationStrategy extends TerminationStrategy
{
    /**
     * Get the started timestamp
     *
     * @return mixed
     */
    public function getStartedTime();

    /**
     * Get the current time
     *
     * @return mixed
     */
    public function getCurrentTime();
}
