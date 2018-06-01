<?php

namespace App\Http\Controllers\Backend\Access\Event\Organization;

use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;
use App\Repositories\Event\OrganizationRepository;
use App\Http\Requests\Backend\Access\Event\ManageOrgRequest;
use Carbon\Carbon as Carbon;

/**
 * Class OrganizationTableController.
 */
class OrganizationTableController extends Controller
{
    /**
     * @var EventRepository
     */
    protected $orgs;

    /**
     * @param OrganizationRepository $orgs
     */
    public function __construct(OrganizationRepository $orgs)
    {
        $this->orgs = $orgs;
    }

    /**
     * @param ViewEventRequest $request
     *
     * @return mixed
     */
    public function __invoke(ManageOrgRequest $request)
    {
        $tables = Datatables::of($this->orgs->getForDataTable())
            ->escapeColumns(['name'])
            ->addColumn('address', function ($org) {
                return collect([
                    $org->address_1,
                    $org->address_2,
                    $org->city,
                    $org->state,
                ])
                    ->filter()
                    ->implode(', ');
            })
            ->addColumn('actions', function ($org) {
                return $org->action_buttons;
            })
            ->make(true);

        return $tables;
    }
}
