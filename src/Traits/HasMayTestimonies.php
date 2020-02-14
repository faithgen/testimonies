<?php

namespace Faithgen\Testimonies\Traits;

use Faithgen\Testimonies\Models\Testimony;

trait HasManyTestimonies
{
    public function tesimonies()
    {
        return $this->hasMany(Testimony::class);
    }
}
