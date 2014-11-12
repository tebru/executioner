<?php
/**
 * File Invokeable.php 
 */

namespace Tebru\Executioner\Attemptor;

/**
 * Interface Invokeable
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface Invokeable
{
    /**
     * Allows class to be called as a functor
     *
     * @return mixed
     */
    public function __invoke();
} 
