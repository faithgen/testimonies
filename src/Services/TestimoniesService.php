<?php

namespace Faithgen\Testimonies\Services;

use Faithgen\Testimonies\Models\Testimony;
use InnoFlash\LaraStart\Services\CRUDServices;

final class TestimoniesService extends CRUDServices
{
    protected Testimony $testimony;

    public function __construct()
    {
        $this->testimony = app(Testimony::class);

        $testimonyId = request()->route('testimony') ?? request('testimony_id');

        if ($testimonyId) {
            $this->testimony = $this->testimony->resolveRouteBinding($testimonyId);
        }
    }

    /**
     * Retrieves an instance of testimony.
     *
     * @return \Faithgen\Testimonies\Models\Testimony
     */
    public function getTestimony(): Testimony
    {
        return $this->testimony;
    }

    /**
     * Makes a list of fields that you do not want to be sent
     * to the create or update methods.
     * Its mainly the fields that you do not have in the messages table.
     *
     * @return array
     */
    public function getUnsetFields(): array
    {
        return ['testimony_id'];
    }

    /**
     * Attaches a parent to the current testimony
     * You can delete this if you do not intent to create testimonys from parent relationships.
     */
    public function getParentRelationship()
    {
        return auth()->user()->testimonies();
    }
}
