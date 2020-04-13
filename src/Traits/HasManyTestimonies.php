<?php

namespace Faithgen\Testimonies\Traits;

use Faithgen\Testimonies\Models\Testimony;
use Illuminate\Database\Eloquent\Relations\Relation;

trait HasManyTestimonies
{
    /**
     * Links a calling object to many testimonies.
     *
     * @return Relation
     */
    public function testimonies(): Relation
    {
        return $this->hasMany(Testimony::class);
    }
}
