<?php

namespace App\Repositories\Event;

use App\Models\Access\User\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Models\Event\Event;
use App\Models\Event\Person\EventVendor;
use App\Models\Event\Person\EventCoordinator;
use App\Services\Access\Facades\Access;
use App\Models\Response\RespSuccess;
use App\Models\Response\RespFailure;

/**
 * Class EventVendorRepository.
 */
class EventVendorRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = EventVendor::class;

    protected $i;

    /**
     * @param RoleRepository $role
     */
    public function __construct()
    {
        $this->i = self::MODEL;
    }

    /**
     * @param int  $status
     * @param bool $trashed
     *
     * @return mixed
     */
    public function getForDataTable()
    {
        /**
         * Note: You must return deleted_at or the User getActionButtonsAttribute won't
         * be able to differentiate what buttons to show for each row.
         */
        $class = self::MODEL;
        $table = with(new $class)->getTable();

        // Nothing here yet
        $dataTableQuery = $class::query()
            ->select([
                "$table.id",
            ]);

        return $dataTableQuery;
    }

    /**
     * @param array $input
     */
    public function create($input)
    {
        // Nothing here yet
    }

    /**
     * @param Event $event
     * @param array $input
     */
    public function update(Event $event, array $input)
    {
        // Nothing here yet
    }


    /**
     * @param Event $event
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete(Event $event)
    {
        // Nothing here yet
    }

    /**
     * @param array $input
     */
    public function newVendor($input)
    {
        $data = $input['data']->all();
        $pers_data = $data['persona'];

        // dd($data);

        // Log::debug(print_r($input, true));

        $user = $this->createUserStub($data);
        $vendor = $this->createVendorStub($pers_data);
        $vendor->fill($pers_data);

        return DB::transaction(function () use ($user, $data, $vendor) {
            if (!$user->save()) {
                return new RespFailure(trans('exceptions.backend.access.users.create_error'));
            }

            $vendor->save();
            //Attach new roles
            $user->attachRoles([config('event.vendor.role.id')]);
            $categories = [];

            //Send confirmation email if requested
            // if (isset($data['confirmation_email']) && $user->confirmed == 0) {
            //     $user->notify(new UserNeedsConfirmation($user->confirmation_code));
            // }
            if (isset($data['organization']['id'])) {
                $vendor->orgs()->attach($data['organization']['id']);
            }

            if (!empty($data['category']) && method_exists($vendor, 'categories')) {
                $categories = $data['category']['id'];
                $vendor->categories()->sync($categories);
            }

            $vendor->save();
            $user->persona()->associate($vendor);
            $user->save();

            return new RespSuccess('Vendor created', [
                'id' => $vendor->id, 
                'name' => $vendor->full_name,
                'categories' => $categories,
            ]);
        });
        
    }


    /**
     * return array of objects
     * @return array
     */
    public function enum()
    {
        // Nothing here yet
    }

    /**
     * return id => name array
     * @return array
     */
    public function enumVendors()
    {
        // Nothing here yet
    }

    /**
     * @param  $input
     *
     * @return mixed
     */
    protected function createUserStub($input)
    {
        $user = new User();
        $user->name = strtolower($input['persona']['first_name'] . '_' . $input['persona']['last_name']);
        $user->email = $input['email'];
        $user->status = (isset($input['status'])) ? $input['status'] : 1;
        $user->confirmation_code = md5(uniqid(mt_rand(), true));
        $user->confirmed = 0;

        return $user;
    }

    /**
     * @param  $input
     *
     * @return mixed
     */
    protected function createVendorStub($input)
    {
        $vendor = new $this->i;
        $vendor->first_name = $input['first_name'];
        $vendor->last_name = $input['last_name'];

        return $vendor;
    }
}
