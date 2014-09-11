<?php
/**
 * File NullClosure.php
 */

namespace Tebru\Executioner\Closure;

/**
 * Class NullClosure
 *
 * Creates a null callable
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class NullClosure
{
    /**
     * Magic invoke method that turns this into a functor
     */
    public function __invoke() {}
} 
