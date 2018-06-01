<?php

namespace App\Http\Controllers\Backend\Access\User;

use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;
use App\Repositories\Backend\Access\User\UserRepository;
use App\Http\Requests\Backend\Access\User\ManageUserRequest;
use Illuminate\Support\Facades\Log;
use App\Models\Access\User\User;
use Carbon\Carbon as Carbon;
use App\Models\Event\Event;

/**
 * Class UserTableController.
 */
class VendorTableController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function __invoke(ManageUserRequest $request)
    {
        return Datatables::of($this->users->getForDataTable($request->get('status'), $request->get('trashed'), $request->get('role')))
            ->escapeColumns(['name', 'email'])
            ->editColumn('confirmed', function ($user) {
                return $user->confirmed_label;
            })
            ->editColumn('created_at', function ($user) {
                return (new Carbon($user->created_at))->format(Event::FORMAT_DATE_DISPLAY);
            })
            ->editColumn('name', function ($user) {
                return User::withTrashed()->find($user->id)->full_name;
            })
            ->addColumn('status', function ($user) {
                return ($user->status == 0) ? '<label class="label label-warning">Pending approval</label>' : '<label class="label label-success">Approved</label>';
            })
            ->addColumn('actions', function ($user) {
                return $user->action_buttons;
            })
            ->withTrashed()
            ->make(true);
    }
}
