<?php

namespace App\Repositories\Event;

use App\Models\Access\User\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Models\Event\Event;
use App\Models\Event\Venue;
use App\Models\Event\Person\EventCoordinator;
use App\Services\Access\Facades\Access;

/**
 * Class VenueRepository.
 */
class VenueRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Venue::class;

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

        $dataTableQuery = $class::query()
            ->select([
                "$table.id",
                "$table.name",
            ]);

        return $dataTableQuery;
    }

    /**
     * @param array $input
     */
    public function create($input)
    {

    }

    /**
     * @param Venue $venue
     * @param array $input
     */
    public function update(Venue $venue, array $input)
    {

    }


    /**
     * @param Venue $venue
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete(Venue $venue)
    {
     
    }

    /**
     * @param array $input
     */
    public function newVenue($input)
    {
        $data = $input['data'];
        $venue = $this->createVenueStub($data);

        if (access()->user()->persona instanceof EventCoordinator) {
            $venue->coordinator()->associate(access()->user()->persona);
        }

        DB::transaction(function () use ($venue, $data) {
            if ($venue->save()) {

                return true;
            }

            return ['msg' => 'Failed to create venue'];
        });

        return ['id' => $venue->id, 'name' => $venue->public_name];
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
            return isset(access()->user()->persona->venues) ? access()->user()->persona->venues->all() : [];
        }

        return [];
    }

    /**
     * return id => name array
     * @return array
     */
    public function enumVenues()
    {
        $venues = $this->enum();
        $resp = [];

        foreach ($venues as $venue) {
            $resp[$venue->id] = $venue->public_name;
        }

        return $resp;
    }

    /**
     * @param  $input
     *
     * @return mixed
     */
    protected function createVenueStub($input)
    {
        $venue = new $this->i;
        $venue->fill($input->toArray());

        return $venue;
    }
}
