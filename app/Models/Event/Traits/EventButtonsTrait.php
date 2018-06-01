<?php

namespace App\Models\Event\Traits;

use App\Services\Access\Facades\Access;

/**
 * Class EventButtonsTrait.
 */
trait EventButtonsTrait {


    /**
     * @return string
     */
    public function getCompleteButtonAttribute()
    {
        
        if ($this->completed) {
            return '';
        }

        if (!Access::allowMultiple(['manage-own-events', 'manage-all-events'])) {
            return '';
        }

        return '<a href="'.route('frontend.event.complete', $this).'" class="btn btn-xs btn-warning" data-swal="complete_event"><i class="icon-check" data-toggle="tooltip" data-placement="top" title="Complete"></i></a> ';
    }

    /**
     * @return string
     */
    public function getShowButtonAttribute()
    {
        return '<a href="'.route('frontend.event.show', $this).'" class="btn btn-xs btn-info"><i class="icon-search4" data-toggle="tooltip" data-placement="top" title="View"></i></a> ';
    }

    /**
     * @return string
     */
    public function getVendorsButtonAttribute()
    {
        if ($this->completed) {
            return '';
        }

        if (!Access::allowMultiple(['manage-own-events', 'manage-all-events'])) {
            return '';
        }

        return '<a href="'.route('frontend.event.vendors.update', $this).'" class="btn btn-xs btn-success"><i class="icon-users3" data-toggle="tooltip" data-placement="top" title="Vendors"></i></a> ';
    }

    /**
     * @return string
     */
    public function getEditButtonAttribute()
    {
        if ($this->completed) {
            return '';
        }

    	if (!Access::allowMultiple(['manage-own-events', 'manage-all-events'])) {
    		return '';
    	}

        return '<a href="'.route('frontend.event.edit', $this).'" class="btn btn-xs btn-primary"><i class="icon-pencil2" data-toggle="tooltip" data-placement="top" title="Edit"></i></a> ';
    }

    /**
     * @return string
     */
    public function getPostponeButtonAttribute()
    {
        if ($this->completed || $this->status == config('event.status.postponed')) {
            return '';
        }

        if (!Access::allowMultiple(['manage-own-events', 'manage-all-events'])) {
            return '';
        }

        return '<a href="'.route('frontend.event.postpone', $this).'" class="btn btn-xs btn-primary" data-swal="postpone_event"><i class="icon-history" data-toggle="tooltip" data-placement="top" title="Postpone"></i></a> ';
    }

    /**
     * @return string
     */
    public function getRequestInviteButtonAttribute($vendor)
    {
        if ($this->completed) {
            return '';
        }

        if (!Access::allowMultiple(['can-be-invited'])) {
            return '';
        }

        if (!$this->can_invite_vendor($vendor)) {
            return '';
        }

        return '<a href="'.route('frontend.event.invite_vendor', $this).'" class="btn btn-xs btn-primary"><i class="icon-envelop" data-toggle="tooltip" data-placement="top" title="Request Invite"></i></a> ';
    }

    /**
     * @return string
     */
    public function getNotesButtonAttribute()
    {
        if ($this->completed) {
            return '';
        }

        if (!Access::allowMultiple(['manage-own-events', 'manage-all-events'])) {
            return '';
        }

        return '<a href="'.route('frontend.event.notes.show', $this).'" class="btn btn-xs btn-success"><i class="icon-edit2" data-toggle="tooltip" data-placement="top" title="Notes"></i></a> ';
    }

    /**
     * @return string
     */
    public function getCancelButtonAttribute()
    {
        if ($this->completed || $this->status == config('event.status.cancelled')) {
            return '';
        }

        if (!Access::allowMultiple(['manage-own-events', 'manage-all-events'])) {
            return '';
        }

        return '<a href="'.route('frontend.event.cancel', $this).'" class="btn btn-xs btn-danger" data-swal="cancel_event"><i class="icon-remove" data-toggle="tooltip" data-placement="top" title="Cancel"></i></a> ';
    }

    /**
     * @return string
     */
    public function getDeleteButtonAttribute()
    {
    	if (!access()->admin()) {
    		return '';
    	}

        return '<a href="'.route('frontend.event.destroy', $this).'"
             data-method="delete"
             data-trans-button-cancel="'.trans('buttons.general.cancel').'"
             data-trans-button-confirm="'.trans('buttons.general.crud.delete').'"
             data-trans-title="'.trans('strings.backend.general.are_you_sure').'"
             class="btn btn-xs btn-danger"><i class="icon-trash-o" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.general.crud.delete').'"></i></a> ';
    }

    public function getPendingButtonAttribute()
    {
         if ($this->completed || $this->status == config('event.status.pending')) {
            return '';
        }

        if (!Access::allowMultiple(['manage-own-events', 'manage-all-events'])) {
            return '';
        }

        return '<a href="'.route('frontend.event.pending', $this).'" class="btn btn-xs btn-primary" data-swal="pending_event"><i class="icon-forward" data-toggle="tooltip" data-placement="top" title="Pending"></i></a> ';
    }    

    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return
            $this->getPendingButtonAttribute() .
            $this->getCompleteButtonAttribute() .
            $this->getPostponeButtonAttribute() .
            $this->getShowButtonAttribute() .
            $this->getEditButtonAttribute() .
            $this->getNotesButtonAttribute() .
            $this->getVendorsButtonAttribute() .
            $this->getCancelButtonAttribute() .
            $this->getDeleteButtonAttribute();
    }

    /**
     * @return string
     */
    public function radius_action_buttons($vendor)
    {
                   
        return
            $this->getRequestInviteButtonAttribute($vendor) .
            $this->getShowButtonAttribute()
            ;
    }

}