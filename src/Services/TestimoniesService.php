<?php

namespace Faithgen\Testimonies\Services;

use Faithgen\Testimonies\Models\Testimony;
use InnoFlash\LaraStart\Services\CRUDServices;
use Illuminate\Database\Eloquent\Model as ParentModel;

final class TestimoniesService extends CRUDServices
{
    private $testimony;
    public function __construct(Testimony $testimony)
    {
        if (request()->has('testimony_id'))
            $this->testimony = Testimony::findOrFail(request('testimony_id'));
        else $this->testimony = $testimony;
    }

    /**
     * Retrives an instance of testimony
     */
    public function getTestimony(): Testimony
    {
        return $this->testimony;
    }

    /**
     * Makes a list of fields that you do not want to be sent
     * to the create or update methods
     * Its mainly the fields that you do not have in the testimonys table
     */
    public function getUnsetFields()
    {
        return ['testimony_id'];
    }

    /**
     * This returns the model found in the constructor 
     * or an instance of the class if no testimony is found
     */
    public function getModel()
    {
        return $this->getTestimony();
    }

    /**
     * Attaches a parent to the current testimony
     * You can delete this if you do not intent to create testimonys from parent relationships
     */
    public function getParentRelationship()
    {
        return [
            auth()->user()->testimonies()
        ];
    }
}
