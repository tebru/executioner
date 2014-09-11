<?php
/**
 * File Wait.php
 */

namespace Tebru\Executioner\Strategy;

/**
 * Class Wait
 *
 * @author Nate Brunette <n@tebru.net>
 */
abstract class Wait implements WaitStrategy
{
    /**
     * Convert seconds to microseconds
     *
     * @param int $seconds
     *
     * @return int
     */
    private function secondsToMicroseconds($seconds)
    {
        return $seconds * 1000000;
    }

    /**
     * {@inheritdoc}
     */
    final public function wait()
    {
        $waitTime = $this->secondsToMicroseconds($this->getWaitTime());
        usleep($waitTime);

        $this->incrementWait();
    }
}
