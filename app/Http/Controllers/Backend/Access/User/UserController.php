<?php

namespace App\Http\Controllers\Backend\Access\User;

use App\Models\Access\User\User;
use App\Http\Controllers\Controller;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Repositories\Backend\Access\User\UserRepository;
use App\Repositories\Event\VendorCategoryRepository;
use App\Repositories\Event\OrganizationRepository;
use App\Http\Requests\Backend\Access\User\StoreUserRequest;
use App\Http\Requests\Backend\Access\User\ManageUserRequest;
use App\Http\Requests\Backend\Access\User\UpdateUserRequest;
use App\Notifications\Backend\VendorApproved;
use App\Notifications\Backend\VendorDenied;
use Illuminate\Support\Facades\Log;

/**
 * Class UserController.
 */
class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var RoleRepository
     */
    protected $roles;

    protected $categories;
    protected $orgs;

    /**
     * @param UserRepository $users
     * @param RoleRepository $roles
     */
    public function __construct(UserRepository $users, RoleRepository $roles, VendorCategoryRepository $categories,
        OrganizationRepository $orgs)
    {
        $this->users = $users;
        $this->roles = $roles;
        $this->categories = $categories;
        $this->orgs = $orgs;
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ManageUserRequest $request)
    {
        return view('app.admin.users');
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function create(ManageUserRequest $request)
    {
        return view('app.admin.user_create')
            ->withRoles($this->roles->getAll())
            ->withUser(access()->user())
            ->withCategoryList($this->categories->enumVendorCategories())
            ->withOrganizationList($this->orgs->enumOrganizations())
            ->withPersonaList(config('event.ev_classes'));
    }

    /**
     * @param StoreUserRequest $request
     *
     * @return mixed
     */
    public function store(StoreUserRequest $request)
    {
        // dd($request->all());
        $this->users->create(['data' => $request->except('assignees_roles'), 'roles' => $request->only('assignees_roles')]);

        return redirect()->route('admin.access.user.index')->withFlashSuccess(trans('alerts.backend.users.created'));
    }

    /**
     * @param User              $user
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function show(User $user, ManageUserRequest $request)
    {
        return view('app.admin.user_show')
            ->withUser($user);
    }

    /**
     * @param User              $user
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function edit(User $user, ManageUserRequest $request)
    {   
        $org_list = $this->orgs->enumOrganizations(!$user->hasRole('Vendor User'));//Pass true to get orgs without vendor_only flag
        // dd($org_list);
        return view('app.admin.user_edit')
            ->withUser($user)
            ->withUserRoles($user->roles->pluck('id')->all())
            ->withRoles($this->roles->getAll())
            ->withPersona($user->persona)
            ->withPersonaList(config('event.ev_classes'))
            ->withOrganizationList($org_list)
            ->withCategoryList($this->categories->enumVendorCategories());
    }

    /**
     * @param User              $user
     * @param UpdateUserRequest $request
     *
     * @return mixed
     */
    public function update(User $user, UpdateUserRequest $request)
    {
        $this->users->update($user, ['data' => $request->except('assignees_roles'), 'roles' => $request->only('assignees_roles')]);

        return redirect()->route('admin.access.user.index')->withFlashSuccess(trans('alerts.backend.users.updated'));
    }

    /**
     * @param User              $user
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function destroy(User $user, ManageUserRequest $request)
    {
        $this->users->delete($user);

        return redirect()->route('admin.access.user.deleted')->withFlashSuccess(trans('alerts.backend.users.deleted'));
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showVendors(ManageUserRequest $request)
    {
        return view('app.admin.vendors');
    }
    
    /**
     * @param UpdateUserRequest $request
     *
     * @return mixed
     */
    public function approveVendor(User $user, UpdateUserRequest $request)
    {
        // dd($request->all());
        $this->users->update($user, ['data' => $request->except('assignees_roles'), 'roles' => $request->only('assignees_roles')]);
        $user->notify(new VendorApproved($user->getNameAttribute()));

        return redirect()->route('admin.access.vendor.index')->withFlashSuccess(trans('alerts.backend.vendor.approved'));
    }

    /**
     * @param UpdateUserRequest $request
     *
     * @return mixed
     */
    public function denyVendor(User $user, UpdateUserRequest $request)
    {
        // dd($request->all());
        // $this->users->update($user, ['data' => $request->except('assignees_roles'), 'roles' => $request->only('assignees_roles')]);
        $this->users->delete($user);
        $user->notify(new VendorDenied($user->getNameAttribute()));

        return redirect()->route('admin.access.vendor.index')->withFlashSuccess(trans('alerts.backend.vendor.denied'));
    }
}
