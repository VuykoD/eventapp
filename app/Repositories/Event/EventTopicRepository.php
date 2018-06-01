<?php

namespace App\Repositories\Event;

use App\Models\Access\User\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Models\Event\Event;
use App\Models\Event\EventTopic;
use App\Models\Event\Person\EventCoordinator;
use App\Services\Access\Facades\Access;

/**
 * Class EventTopicRepository.
 */
class EventTopicRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = EventTopic::class;

    protected $i;

    /**
     * 
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
     * @param EventTopic $event_topic
     * @param array $input
     */
    public function update(EventTopic $event_topic, array $input)
    {

    }


    /**
     * @param EventTopic $event_topic
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete(EventTopic $event_topic)
    {
     
    }

    /**
     * @param array $input
     * @return array
     */
    public function validateIds($input)
    {
        return collect($input)
            ->transform(function($item, $key) {
                return trim($item);
            })
            ->unique()
            ->transform(function($item, $key) {
                if (ctype_digit($item)) {
                    return $item;
                }

                $res = $this->newEventTopic(['data' => ['name' => $item, 'custom' => 1]]);
                return isset($res['id']) ? $res['id'] : false;
            })
            ->filter()
            ->all();
    }

    /**
     * @param array $input
     */
    public function newEventTopic($input)
    {
        $data = $input['data'];
        if (is_object($data)) {
            $data = $data.toArray();
        }

        $event_topic = $this->createEventTopicStub($data);

        DB::transaction(function () use ($event_topic, $data) {
            if ($event_topic->save()) {

                return true;
            }

            return ['msg' => 'Failed to create event topic'];
        });

        return ['id' => $event_topic->id, 'name' => $event_topic->name];
    }


    /**
     * return array of objects
     * @return array
     */
    public function enum()
    {
        if (Access::allow('view-events')) {
            return $this->i::where('custom', '=', 0)->get();
        }

        return [];
    }

    /**
     * return id => name array
     * @return array
     */
    public function enumEventTopics()
    {
        $topics = $this->enum();
        $resp = [];

        foreach ($topics as $topic) {
            $resp[$topic->id] = $topic->name; 
        }

        return $resp;
    }

    /**
     * @param  $input
     *
     * @return mixed
     */
    protected function createEventTopicStub($input)
    {
        return $this->i::create($input);
    }
}
