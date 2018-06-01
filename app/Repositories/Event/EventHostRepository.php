<?php

namespace App\Repositories\Event;

use App\Models\Access\User\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Models\Event\Event;
use App\Models\Event\Person\EventHost;
use App\Models\Event\Person\EventCoordinator;
use App\Services\Access\Facades\Access;

/**
 * Class EventHostRepository.
 */
class EventHostRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = EventHost::class;

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
    public function newHost($input)
    {
        $data = $input['data'];
        $pers_data = $input['data']['persona'];

        // Log::debug(print_r($input, true));

        $user = $this->createUserStub($data);
        $host = $this->createHostStub($pers_data);
        $host->fill($pers_data);

        DB::transaction(function () use ($user, $data, $host) {
            if ($user->save()) {

                $host->save();
                //Attach new roles
                $user->attachRoles([config('event.host.role.id')]);

                //Send confirmation email if requested
                // if (isset($data['confirmation_email']) && $user->confirmed == 0) {
                //     $user->notify(new UserNeedsConfirmation($user->confirmation_code));
                // }
                if (isset($data['organization']['id'])) {
                    $host->orgs()->attach($data['organization']['id']);
                }

                if (access()->user()->persona instanceof EventCoordinator) {
                    $host->coordinator()->associate(access()->user()->persona);
                }

                $host->save();
                $user->persona()->associate($host);
                $user->save();

                return true;
            }

            return ['msg' => (trans('exceptions.backend.access.users.create_error'))];
        });

        return ['id' => $user->persona->id, 'name' => $host->getFullName()];
    }


    /**
     * return array of objects
     * @return array
     */
    public function enum()
    {
        if (Access::allow('manage-all-events')) {
            return $this->i::all();
        }

        if (Access::allow('manage-own-events') && isset(access()->user()->persona)) {
            return isset(access()->user()->persona->hosts) ? access()->user()->persona->hosts->all() : [];
        }

        return [];
    }

    /**
     * return id => name array
     * @return array
     */
    public function enumHosts()
    {
        $hosts = $this->enum();
        $resp = [];

        foreach ($hosts as $host) {
            $resp[$host->id] = $host->full_name;
        }

        return $resp;
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
        $user->status = 1;
        $user->confirmation_code = md5(uniqid(mt_rand(), true));
        $user->confirmed = 0;

        return $user;
    }

    /**
     * @param  $input
     *
     * @return mixed
     */
    protected function createHostStub($input)
    {
        $host = new $this->i;
        $host->first_name = $input['first_name'];
        $host->last_name = $input['last_name'];

        return $host;
    }
}
