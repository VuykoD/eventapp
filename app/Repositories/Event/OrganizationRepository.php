<?php

namespace App\Repositories\Event;

use App\Models\Access\User\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Models\Event\Event;
use App\Models\Event\Organization;
use App\Models\Event\Person\EventCoordinator;
use App\Services\Access\Facades\Access;
use App\Exceptions\ModelCrudException;

/**
 * Class OrganizationRepository.
 */
class OrganizationRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Organization::class;

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
                "$table.address_1",
                "$table.address_2",
                "$table.city",
                "$table.state",
            ]);

        return $dataTableQuery;
    }

    /**
     * @param array $input
     */
    public function create($input)
    {
        if (!is_array($input['data'])) {
            $data = $input['data']->toArray();
        } else {
            $data = $input['data'];
        }
        $org = $this->createOrgStub($data);

        return DB::transaction(function () use ($org, $data) {
            if ($org->save()) {

                return $org;
            }

            throw new GeneralException('Failed to create organization');
        });
    }

    /**
     * @param Organization $org
     * @param array $input
     */
    public function update(Organization $org, array $input)
    {
        $data = $input['data']->toArray();

        return DB::transaction(function () use ($org, $data) {

            if ($org->update($data)) {
                // Update relations
                
                return $org;
            }

            throw new GeneralException('Failed to update organization');
        });
    }


    /**
     * @param Organization $org
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete(Organization $org)
    {
        if ($org->delete()) {
            return true;
        }

        throw new GeneralException('Organization deletion failure');
    }

    /**
     * @param array $input
     */
    public function newOrganization($input)
    {
        try 
        {
            $org = $this->create($input);
        }
        catch (Exception $e) 
        {
            return ['id' => null, 'msg' => $e->getMessage()];
        }

        return ['id' => $org->id, 'name' => $org->name];
    }


    /**
     * return array of objects
     * @return array
     */
    public function enum($vendor_only = false)
    {
        if ($vendor_only) {
            return $this->i::where('vendor_only', 0)->get();
        }
        return $this->i::all();
    }

    /**
     * return id => name array
     * @return array
     */
    public function enumOrganizations($vendor_only = false)
    {
        $orgs = $this->enum($vendor_only);
        $resp = [];

        foreach ($orgs as $org) {
            $resp[$org->id] = $org->name;
        }

        return $resp;
    }

    /**
     * @param  $input
     *
     * @return mixed
     */
    protected function createOrgStub($input)
    {
        $org = $this->i::create($input);
        
        return $org;
    }
}
