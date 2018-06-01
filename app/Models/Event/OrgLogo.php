<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class OrgLogo extends Model
{
    protected $fillable = ['filename', 'path', 'url', 'organization_id'];

    
    /**
     * Get user.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\Access\User\User', 'user_id');
    }
}
