<?php

namespace App\Models\Access\User;

use Illuminate\Notifications\Notifiable;
use App\Models\Access\User\Traits\UserAccess;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Access\User\Traits\Scope\UserScope;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Access\User\Traits\UserSendPasswordReset;
use App\Models\Access\User\Traits\Attribute\UserAttribute;
use App\Models\Access\User\Traits\Relationship\UserRelationship;
use App\Models\Response\RespSuccess;
use App\Models\Response\RespFailure;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Event\Avatar;
use Intervention\Image\Facades\Image;
use Illuminate\Http\File;

/**
 * Class User.
 */
class User extends Authenticatable
{
    use UserScope,
        UserAccess,
        Notifiable,
        SoftDeletes,
        UserAttribute,
        UserRelationship,
        UserSendPasswordReset;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'status', 'confirmation_code', 'confirmed', 'org_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('access.users_table');
    }


    public function upload_avatar($request) {
        $filename = $request->filename;
        $path = str_random() . str_random() . str_random() . '.jpg';
        $img = Image::make(file_get_contents($request->avatar))
            ->widen(500, function ($constraint) {
                $constraint->upsize();
            });

        Storage::disk('avatars')->put($path, $img->stream());
        $url = Storage::disk('avatars')->url($path);
        $url = (substr($url, 0, 1) != '/') ? '/' . $url : $url;
        Avatar::where('user_id', $this->id)->delete();
        Avatar::create(['filename' => $filename, 'path' => $path, 'url' => $url, 'user_id' => $this->id]);
        return new RespSuccess('Avatar Uploaded');
    }

}
