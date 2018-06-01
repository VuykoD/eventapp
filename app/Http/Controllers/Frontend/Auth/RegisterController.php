<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Events\Frontend\Auth\UserRegistered;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Requests\Frontend\Auth\RegisterRequest;
use App\Repositories\Frontend\Access\User\UserRepository;
use App\Repositories\Event\OrganizationRepository;
// use App\Repositories\Event\EventVendorRepository;
use App\Models\Event\Organization;

/**
 * Class RegisterController.
 */
class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * @var UserRepository
     */
    protected $user;

    /**
     * RegisterController constructor.
     *
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        // Where to redirect users after registering
        $this->redirectTo = route('frontend.user.index');

        $this->user = $user;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.vendor_register')->with(['cats' => \App\Models\Event\VendorCategory::All()]);
    }

    /**
     * @param RegisterRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register(RegisterRequest $request)
    {
        if (config('access.users.confirm_email')) {
            $user = $this->user->create($request->all());
            event(new UserRegistered($user));

            return redirect($this->redirectPath())->withFlashSuccess(trans('exceptions.frontend.auth.confirmation.created_confirm'));
        } else {
            access()->login($this->user->create($request->all()));
            event(new UserRegistered(access()->user()));

            return redirect($this->redirectPath());
        }
    }

    /**
     * @param RegisterRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    function registerVendor(RegisterRequest $request)
    {
        // dd($request->all());
        $org_repo = new OrganizationRepository;
        if ($request->organization != 'false') {
            $input = ['data' => $request->organization];
            $org_stub = $org_repo->newOrganization($input);
            // $org = \DB::table('organizations')->where('id', $org['id'])->first();
            // $org->code = Organization::getRandomCode();
            $org = Organization::find($org_stub['id']);
            $org->code = $org->getRandomCode();
            $org->save();
        } else {
            // $org = \DB::table('organizations')->where('code', $request->org_id)->first();
            $org = Organization::where('code', $request->org_id)->first();
        }

        $request->request->remove('organization');
        $request->request->remove('name');
        // dd($request->all());
        $backend_user_repo = new \App\Repositories\Backend\Access\User\UserRepository(new \App\Repositories\Backend\Access\Role\RoleRepository);
        $backend_user_repo->create(['data' => $request->except('assignees_roles'), 'roles' => $request->only('assignees_roles')]);

        return response()->json(['message' => 'Please check your email inbox for a confirmation link. After confirming your email our administrator will check your data to approve your submission. You will be notified via email about his decision.'], 200);
    }

    /**
     * @param RegisterRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    function checkOrgCode(\Illuminate\Http\Request $request)
    {
        $org = \DB::table('organizations')->where('code', $request->code)->first();
        if (!$org) {
            return response()->json(['message' => 'Error: no record for organization with this code'], 404);
        } else {
            return response()->json(['message' => 'Success', 'org_id' => $org->id], 200);
        }
    }
}
