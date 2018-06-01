<?php

namespace App\Repositories\Event;

use App\Models\Access\User\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Models\Event\Event;
use App\Models\Event\EventType;
use App\Models\Event\Person\EventCoordinator;
use App\Services\Access\Facades\Access;

/**
 * Class EventTypeRepository.
 */
class EventTypeRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = EventType::class;

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
     * @param EventType $event_type
     * @param array $input
     */
    public function update(EventType $event_type, array $input)
    {

    }


    /**
     * @param EventType $event_type
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete(EventType $event_type)
    {
     
    }

    /**
     * @param array $input
     */
    public function newEventType($input)
    {
        $data = $input['data'];
        $event_type = $this->createEventTypeStub($data);


        DB::transaction(function () use ($event_type, $data) {
            if ($event_type->save()) {

                return true;
            }

            return ['msg' => 'Failed to create venue'];
        });

        return ['id' => $event_type->id, 'name' => $event_type->name];
    }


    /**
     * return array of objects
     * @return array
     */
    public function enum()
    {
        if (Access::allow('view-events')) {
            return $this->i::all();
        }

        return [];
    }

    /**
     * return id => name array
     * @return array
     */
    public function enumEventTypes()
    {
        $types = $this->enum();
        $resp = [];

        foreach ($types as $type) {
            $resp[$type->id] = $type->name 
            . (isset($type->group_size) ? ' ' . $type->group_size : '')
            . (isset($type->additional) ? ' >> ' . $type->additional : '');
        }

        return $resp;
    }

    /**
     * @param  $input
     *
     * @return mixed
     */
    protected function createEventTypeStub($input)
    {
        $venue = new $this->i;
        $venue->fill($input->toArray());

        return $venue;
    }
}
