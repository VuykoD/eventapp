<?php

namespace App\Repositories\Backend\Access\User;

use App\Models\Access\User\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use App\Events\Backend\Access\User\UserCreated;
use App\Events\Backend\Access\User\UserDeleted;
use App\Events\Backend\Access\User\UserUpdated;
use App\Events\Backend\Access\User\UserRestored;
use App\Events\Backend\Access\User\UserDeactivated;
use App\Events\Backend\Access\User\UserReactivated;
use App\Events\Backend\Access\User\UserPasswordChanged;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Events\Backend\Access\User\UserPermanentlyDeleted;
use App\Notifications\Frontend\Auth\UserNeedsConfirmation;
use Illuminate\Support\Facades\Log;

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = User::class;

    /**
     * @var RoleRepository
     */
    protected $role;

    /**
     * @param RoleRepository $role
     */
    public function __construct(RoleRepository $role)
    {
        $this->role = $role;
    }

    /**
     * @param        $permissions
     * @param string $by
     *
     * @return mixed
     */
    public function getByPermission($permissions, $by = 'name')
    {
        if (! is_array($permissions)) {
            $permissions = [$permissions];
        }

        return $this->query()->whereHas('roles.permissions', function ($query) use ($permissions, $by) {
            $query->whereIn('permissions.'.$by, $permissions);
        })->get();
    }

    /**
     * @param        $roles
     * @param string $by
     *
     * @return mixed
     */
    public function getByRole($roles, $by = 'name')
    {
        if (! is_array($roles)) {
            $roles = [$roles];
        }

        return $this->query()->whereHas('roles', function ($query) use ($roles, $by) {
            $query->whereIn('roles.'.$by, $roles);
        })->get();
    }

    /**
     * @param int  $status
     * @param bool $trashed
     *
     * @return mixed
     */
    public function getForDataTable($status = 1, $trashed = false, $role = false)
    {
        /**
         * Note: You must return deleted_at or the User getActionButtonsAttribute won't
         * be able to differentiate what buttons to show for each row.
         */
        $dataTableQuery = $this->query()
            ->with('roles')
            ->select([
                config('access.users_table').'.id',
                config('access.users_table').'.name',
                config('access.users_table').'.email',
                config('access.users_table').'.status',
                config('access.users_table').'.confirmed',
                config('access.users_table').'.created_at',
                config('access.users_table').'.updated_at',
                config('access.users_table').'.deleted_at',
            ]);

        // Non Admin users can't see admins
        if (!access()->admin()) {
            $dataTableQuery = $dataTableQuery->whereDoesntHave('roles', function ($query) {
                $query->where('name', '=', config('access.role_admin'));
            });
        }

        if ($trashed == 'true') {
            return $dataTableQuery->onlyTrashed();
        }

        // print_r($role);
        if ($role) {
            return $dataTableQuery->whereHas('roles', function($query) use ($role) {
                $query->where('name', '=', $role);
            });
        } else {
            return $dataTableQuery->whereHas('roles', function($query) {
                $query->where('name', '!=', 'Vendor User');
            })->active($status);
        }

        // active() is a scope on the UserScope trait
        return $dataTableQuery->active($status);
    }

    /**
     * @param Model $input
     */
    public function create($input)
    {
        $data = $input['data'];
        $roles = $input['roles'];

        $user = $this->createUserStub($data);
        if (isset($data['org_id'])) {
            $user->org_id = $data['org_id'];
            $data['organization_id'] = DB::table('organizations')->where('code', '=', $user->org_id)->first()->id;
        }

        DB::transaction(function () use ($user, $data, $roles) {
            if ($user->save()) {

                //User Created, Validate Roles
                if (! count($roles['assignees_roles'])) {
                    throw new GeneralException(trans('exceptions.backend.access.users.role_needed_create'));
                }

                if (!access()->admin() && collect($roles['assignees_roles'])->contains('1')) {
                    $roles['assignees_roles'] = collect($roles['assignees_roles'])
                        ->reject(function ($value, $key) {
                            return $value == 1;
                        })
                        ->all();
                }

                //Attach new roles
                $user->attachRoles($roles['assignees_roles']);

                //Send confirmation email if requested
                if (isset($data['confirmation_email']) && $user->confirmed == 0) {
                    $user->notify(new UserNeedsConfirmation($user->confirmation_code));
                }

                // Update/create persona record
                if (!empty($data['persona']) && !empty($data['persona']['class'])) {
                    // 
                    $data['persona']['health_plans'] = isset($data['persona']['health_plans']) && is_array($data['persona']['health_plans'])
                        ? implode(',', $data['persona']['health_plans'])
                        : null;
                    $data['persona']['health_insurer'] = $data['persona']['health_insurer'] ?? null;

                    $persona = $data['persona']['class']::create($data['persona']);
                    // Event Vendor specific
                    if (!empty($data['category']) && method_exists($persona, 'categories')) {
                        $persona->categories()->sync($data['category']['id']);
                    }
                    if (!empty($data['organization_id']) && method_exists($persona, 'orgs')) {
                        $persona->orgs()->sync([$data['organization_id']]);
                    }
                    $user->persona()->associate($persona);
                    $user->save();
                }

                event(new UserCreated($user));

                return true;
            }

            throw new GeneralException(trans('exceptions.backend.access.users.create_error'));
        });
    }

    /**
     * @param Model $user
     * @param array $input
     */
    public function update(Model $user, array $input)
    {
        $data = $input['data'];
        $roles = $input['roles'];

        // Log::debug(print_r($user, true));
        // Log::debug(print_r($input, true));


        $this->checkUserByEmail($data, $user);

        if (!access()->admin() && collect($roles['assignees_roles'])->contains('1')) {
            $roles['assignees_roles'] = collect($roles['assignees_roles'])
                ->reject(function ($value, $key) {
                    return $value == 1;
                })
                ->all();
        }

        DB::transaction(function () use ($user, $data, $roles) {
            if ($user->update($data)) {
                // Update/create persona record
                if (!empty($data['persona'])) {

                    // 
                    $data['persona']['health_plans'] = isset($data['persona']['health_plans']) && is_array($data['persona']['health_plans'])
                        ? implode(',', $data['persona']['health_plans'])
                        : null;
                    $data['persona']['health_insurer'] = $data['persona']['health_insurer'] ?? null;

                    if (!isset($user->persona) && !empty($data['persona']['class'])) {
                        $persona = $data['persona']['class']::create($data['persona']);
                        $user->persona()->associate($persona);
                    }
                    if (isset($user->persona)) {
                        $user->persona->update($data['persona']);
                        $user->persona->save();

                        $persona = $user->persona;
                        if (!empty($data['category']) && method_exists($persona, 'categories')) {
                            $persona->categories()->sync($data['category']['id']);
                        }
                        if (!empty($data['organization_id']) && method_exists($persona, 'orgs')) {
                            $persona->orgs()->sync([$data['organization_id']]);
                        }
                    }
                }
                //For whatever reason this just wont work in the above call, so a second is needed for now
                $user->status = isset($data['status']) ? 1 : 0;
                $user->confirmed = isset($data['confirmed']) ? 1 : 0;
                $user->save();

                $this->checkUserRolesCount($roles);
                $this->flushRoles($roles, $user);

                event(new UserUpdated($user));

                return true;
            }

            throw new GeneralException(trans('exceptions.backend.access.users.update_error'));
        });
    }

    /**
     * @param Model $user
     * @param $input
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function updatePassword(Model $user, $input)
    {
        $user->password = bcrypt($input['password']);

        if ($user->save()) {
            event(new UserPasswordChanged($user));

            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.update_password_error'));
    }

    /**
     * @param Model $user
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete(Model $user)
    {
        if (access()->id() == $user->id) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_delete_self'));
        }

        // Admin user (id 1) can not be deleted
        if ($user->id == 1) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_delete_admin'));
        }

        if ($user->delete()) {
            event(new UserDeleted($user));

            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.delete_error'));
    }

    /**
     * @param Model $user
     *
     * @throws GeneralException
     */
    public function forceDelete(Model $user)
    {
        if (is_null($user->deleted_at)) {
            throw new GeneralException(trans('exceptions.backend.access.users.delete_first'));
        }

        DB::transaction(function () use ($user) {
            if (isset($user->persona)) {
                $user->persona->delete();
            }
            
            if ($user->forceDelete()) {
                event(new UserPermanentlyDeleted($user));

                return true;
            }

            throw new GeneralException(trans('exceptions.backend.access.users.delete_error'));
        });
    }

    /**
     * @param Model $user
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function restore(Model $user)
    {
        if (is_null($user->deleted_at)) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_restore'));
        }

        if ($user->restore()) {
            event(new UserRestored($user));

            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.restore_error'));
    }

    /**
     * @param Model $user
     * @param $status
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function mark(Model $user, $status)
    {
        if (access()->id() == $user->id && $status == 0) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_deactivate_self'));
        }

        if ($user->id == 1) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_deactivate_admin'));
        }

        $user->status = $status;

        switch ($status) {
            case 0:
                event(new UserDeactivated($user));
            break;

            case 1:
                event(new UserReactivated($user));
            break;
        }

        if ($user->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.mark_error'));
    }

    /**
     * @param  $input
     * @param  $user
     *
     * @throws GeneralException
     */
    protected function checkUserByEmail($input, $user)
    {
        //Figure out if email is not the same
        if ($user->email != $input['email']) {
            //Check to see if email exists
            if ($this->query()->where('email', '=', $input['email'])->first()) {
                throw new GeneralException(trans('exceptions.backend.access.users.email_error'));
            }
        }
    }

    /**
     * @param $roles
     * @param $user
     */
    protected function flushRoles($roles, $user)
    {
        //Flush roles out, then add array of new ones
        $user->detachRoles($user->roles);
        $user->attachRoles($roles['assignees_roles']);
    }

    /**
     * @param  $roles
     *
     * @throws GeneralException
     */
    protected function checkUserRolesCount($roles)
    {
        //User Updated, Update Roles
        //Validate that there's at least one role chosen
        if (count($roles['assignees_roles']) == 0) {
            throw new GeneralException(trans('exceptions.backend.access.users.role_needed'));
        }
    }

    /**
     * @param  $input
     *
     * @return mixed
     */
    protected function createUserStub($input)
    {
        $user = self::MODEL;
        $user = new $user();
        $first_name = $input['persona']['first_name'] ?? '';
        $last_name = $input['persona']['last_name'] ?? '';
        $user->name = $input['name'] ?? ($first_name . '_' . $last_name);
        $user->email = $input['email'];
        $user->password = bcrypt($input['password']);
        $user->status = isset($input['status']) ? $input['status'] : 0;
        $user->confirmation_code = md5(uniqid(mt_rand(), true));
        $user->confirmed = isset($input['confirmed']) ? $input['confirmed'] : 0;

        return $user;
    }
}
