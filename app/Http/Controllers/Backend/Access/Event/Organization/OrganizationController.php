<?php

namespace App\Http\Controllers\Backend\Access\Event\Organization;

use App\Models\Event\Organization;
use App\Http\Controllers\Controller;
use App\Repositories\Event\OrganizationRepository;
use App\Http\Requests\Backend\Access\Event\ManageOrgRequest;

/**
 * Class RoleController.
 */
class OrganizationController extends Controller
{
    /**
     * @var OrganizationRepository
     */
    protected $orgs;

    /**
     * @param OrganizationRepository       $orgs
     */
    public function __construct(OrganizationRepository $orgs)
    {
        $this->orgs = $orgs;
    }

    /**
     * @param ManageOrgRequest $request
     *
     * @return mixed
     */
    public function index(ManageOrgRequest $request)
    {
        return view('app.admin.orgs');
    }


    /**
     * @param Organization              $org
     * @param ManageOrgRequest $request
     *
     * @return mixed
     */
    public function show(Organization $org, ManageOrgRequest $request)
    {
        return view('app.admin.org-view')
            ->withOrganization($org); 
    }

    /**
     * @param ManageOrgRequest $request
     *
     * @return mixed
     */
    public function create(ManageOrgRequest $request)
    {
        return view('app.admin.org-create')
            ->withOrganizationFields(with(new Organization)->getFields())
            ;
    }

    /**
     * @param ManageOrgRequest $request
     *
     * @return mixed
     */
    public function store(ManageOrgRequest $request)
    {
        $this->orgs->create(['data' => $request]);

        return redirect()->route('admin.access.org.index')->withFlashSuccess('Organization created');
    }

    /**
     * @param Organization              $org
     * @param ManageOrgRequest $request
     *
     * @return mixed
     */
    public function edit(Organization $org, ManageOrgRequest $request)
    {
        return view('app.admin.org-edit')
            ->withOrganization($org)
            ->withOrganizationFields(with(new Organization)->getFields())
            ->withOwnerType('org')
            ->withOwnerId($org->id)
            ->withOwnerUrl($org->logo_url())
            ;
    }

    /**
     * @param Organization              $org
     * @param ManageOrgRequest $request
     *
     * @return mixed
     */
    public function update(Organization $org, ManageOrgRequest $request)
    {
        $this->orgs->update($org, ['data' => $request]);

        return redirect()->route('admin.access.org.index')->withFlashSuccess('Organization updated');
    }

    /**
     * @param Organization              $org
     * @param ManageOrgRequest $request
     *
     * @return mixed
     */
    public function destroy(Organization $org, ManageOrgRequest $request)
    {
        $this->orgs->delete($org);

        return redirect()->route('admin.access.org.index')->withFlashSuccess('Organization deleted');
    }
}
