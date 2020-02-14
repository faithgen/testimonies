<?php

namespace Faithgen\Testimonies;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Faithgen\Testimonies\Skeleton\SkeletonClass
 */
final class TestimoniesFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'testimonies';
    }
}
