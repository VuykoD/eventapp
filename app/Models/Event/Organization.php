<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;
use App\Models\Response\RespSuccess;
use App\Models\Response\RespFailure;
use Illuminate\Support\Facades\Storage;
use App\Models\Event\OrgLogo;
use Intervention\Image\Facades\Image;
use Illuminate\Http\File;

class Organization extends Model
{

    use FieldsTrait;

    // fillable fields
    protected $fillable = ['name', 'address_1', 'address_2', 'city', 'state', 'zip', 'phone', 'website',
        'lat_lng', 'code', 'vendor_only'];

    protected $form_fields = [
        'name' => [
            'placeholder' => 'Organization Name',
            'type' => 'text',
            'sort' => 11,
            'attributes' => [
                'required' => '',
            ],
        ],
        'address_1' => [
            'placeholder' => 'Address 1',
            'type' => 'text',
            'sort' => 12,
            'attributes' => [
                'required' => '',
            ],
        ],
        'address_2' => [
            'placeholder' => 'Address 2',
            'type' => 'text',
            'sort' => 13,
            'attributes' => [
            ],
        ],
        'city' => [
            'placeholder' => 'City',
            'type' => 'text',
            'sort' => 14,
            'attributes' => [
                'required' => '',
            ],
        ],
        'state' => [
            'placeholder' => 'State',
            'type' => 'text',
            'sort' => 15,
            'attributes' => [
            ],
        ],
        'zip' => [
            'placeholder' => 'Zip',
            'type' => 'text',
            'sort' => 16,
            'attributes' => [
            ],
        ],
        'phone' => [
            'placeholder' => 'Phone',
            'type' => 'text',
            'sort' => 17,
            'attributes' => [
            ],
        ],
        'website' => [
            'placeholder' => 'Website',
            'type' => 'text',
            'sort' => 18,
            'attributes' => [
            ],
        ],
    ];


    /**
     * Get all of the event coordinators that are assigned this company.
     */
    public function coordinators()
    {
        return $this->morphedByMany('App\Models\Event\Person\EventCoordinator', 'persona', 'pivot_persona_org', 'organization_id', 'persona_id');
    }

    /**
     * Get all of the event hosts that are assigned this company.
     */
    public function hosts()
    {
        return $this->morphedByMany('App\Models\Event\Person\EventHost', 'persona', 'pivot_persona_org', 'organization_id', 'persona_id');
    }

    /**
     * Get all of the event vendors that are assigned this company.
     */
    public function vendors()
    {
        return $this->morphedByMany('App\Models\Event\Person\EventVendor', 'persona', 'pivot_persona_org', 'organization_id', 'persona_id');
    }

    /**
     * Get all of the logos for the post.
     */
    public function logos()
    {
        return $this->morphToMany('App\Models\Event\CompanyImage', 'logo_image');
    }

    /**
     * @return string
     */
    public function getFullAddressAttribute()
    {
        return collect([
                    $this->address_1,
                    $this->address_2,
                    $this->city,
                    $this->state,
                    $this->zip,
                ])
                    ->filter()
                    ->implode(', ');
    }

    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {

        return $this->getShowButtonAttribute().
            $this->getEditButtonAttribute().
            $this->getDeleteButtonAttribute();
    }


    /**
     * @return string
     */
    public function getShowButtonAttribute()
    {
        return '<a href="'.route('admin.access.org.show', $this).'" class="btn btn-xs btn-info"><i class="icon-search4" data-toggle="tooltip" data-placement="top" title="View"></i></a> ';
    }

    /**
     * @return string
     */
    public function getEditButtonAttribute()
    {
        return '<a href="'.route('admin.access.org.edit', $this).'" class="btn btn-xs btn-primary"><i class="icon-pencil2" data-toggle="tooltip" data-placement="top" title="Edit"></i></a> ';
    }

    /**
     * @return string
     */
    public function getDeleteButtonAttribute()
    {
        return '<a href="'.route('admin.access.org.destroy', $this).'"
             data-method="delete"
             data-trans-button-cancel="'.trans('buttons.general.cancel').'"
             data-trans-button-confirm="'.trans('buttons.general.crud.delete').'"
             data-trans-title="'.trans('strings.backend.general.are_you_sure').'"
             class="btn btn-xs btn-danger"><i class="icon-trash-o" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.general.crud.delete').'"></i></a> ';
    }


    public function upload_logo($request) {
        $filename = $request->filename;
        $path = str_random() . str_random() . str_random() . '.jpg';
        $img = Image::make(file_get_contents($request->avatar))
            ->widen(500, function ($constraint) {
                $constraint->upsize();
            });

        Storage::disk('logos')->put($path, $img->stream());
        $url = Storage::disk('logos')->url($path);
        $url = (substr($url, 0, 1) != '/') ? '/' . $url : $url;
        OrgLogo::where('organization_id', $this->id)->delete();
        OrgLogo::create(['filename' => $filename, 'path' => $path, 'url' => $url, 'organization_id' => $this->id]);
        
        return new RespSuccess('Logo Uploaded');
    }

    /**
     * Get user.
     */
    public function logo_url()
    {
        $model = OrgLogo::where('organization_id', $this->id)
            ->first();

        if (!isset($model)) {
            return config('event.avatar.default');
        }

        return $model->url;
    }

    /*
    * return string
    */
    public function getRandomCode()
    {
        $code = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
        $validator = \Validator::make(['code' => $code], ['code' => 'unique:organizations,code']);
        if ($validator->fails()) {
            $this->getRandomCode();
        }
        return $code;
    }

}
