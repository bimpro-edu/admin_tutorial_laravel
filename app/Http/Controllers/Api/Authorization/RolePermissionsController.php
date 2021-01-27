<?php

namespace ThaoHR\Http\Controllers\Api\Authorization;

use Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use ThaoHR\Events\Role\PermissionsUpdated;
use ThaoHR\Http\Controllers\Api\ApiController;
use ThaoHR\Http\Requests\Role\CreateRoleRequest;
use ThaoHR\Http\Requests\Role\RemoveRoleRequest;
use ThaoHR\Http\Requests\Role\UpdateRolePermissionsRequest;
use ThaoHR\Http\Requests\Role\UpdateRoleRequest;
use ThaoHR\Repositories\Role\RoleRepository;
use ThaoHR\Repositories\User\UserRepository;
use ThaoHR\Role;
use ThaoHR\Transformers\PermissionTransformer;
use ThaoHR\Transformers\RoleTransformer;

/**
 * Class RolePermissionsController
 * @package ThaoHR\Http\Controllers\Api
 */
class RolePermissionsController extends ApiController
{
    /**
     * @var RoleRepository
     */
    private $roles;

    public function __construct(RoleRepository $roles)
    {
        $this->roles = $roles;
        $this->middleware('auth');
        $this->middleware('permission:permissions.manage');
    }

    public function show(Role $role)
    {
        return $this->respondWithCollection(
            $role->cachedPermissions(),
            new PermissionTransformer
        );
    }

    /**
     * Update specified role.
     * @param Role $role
     * @param UpdateRolePermissionsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Role $role, UpdateRolePermissionsRequest $request)
    {
        $this->roles->updatePermissions(
            $role->id,
            $request->permissions
        );

        event(new PermissionsUpdated);

        return $this->respondWithCollection(
            $role->cachedPermissions(),
            new PermissionTransformer
        );
    }
}
