<?php
/**
 * File NullWaitStrategy.php
 */

namespace Tebru\Executioner\Strategy\Wait;

use Tebru\Executioner\Strategy\WaitStrategy;

/**
 * Class NullWaitStrategy
 *
 * Use this to ignore waits.  This is functionally the same as other wait strategies
 * with 0 values, but more explicit.
 *
 * @author Nate Brunette <n@tebru.net>
 */
class NullWaitStrategy implements WaitStrategy
{
    /**
     * {@inheritdoc}
     */
    public function wait()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getWaitTime()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function incrementWait()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        return null;
    }
}
