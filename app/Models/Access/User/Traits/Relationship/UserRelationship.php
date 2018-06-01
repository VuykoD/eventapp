<?php

namespace App\Models\Access\User\Traits\Relationship;

use App\Models\Event\Avatar;

/**
 * Class UserRelationship.
 */
trait UserRelationship
{
    /**
     * Many-to-Many relations with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(config('access.role'), config('access.role_user_table'), 'user_id', 'role_id');
    }

    /**
    * Get persona model.
    */
    public function persona()
    {
		return $this->morphTo();
    }

    /**
     * @return mixed
     */
    public function getPersonaCheckIdAttribute()
    {
        return isset($this->persona_id) ? $this->persona_id : null;
    }

    /**
     * @return boolean
     */
    public function getHasOrgAttribute()
    {
        if (!isset($this->persona_id)) {
            return false;
        }

        return method_exists($this->persona, 'orgs');
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        if (!isset($this->persona_id)) {
            return $this->name;
        }

        return $this->persona->user_name;
    }

    /**
     * Get user.
     */
    public function avatar_url()
    {
        $model = Avatar::where('user_id', $this->id)
            ->first();

        if (!isset($model)) {
            return config('event.avatar.default');
        }

        return $model->url;
    }

    /**
     * Get user.
     */
    public function small_avatar_url()
    {
        $model = Avatar::where('user_id', $this->id)
            ->first();

        if (!isset($model)) {
            return config('event.avatar.small');
        }

        return $model->url;
    }


}
