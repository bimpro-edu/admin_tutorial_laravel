<?php

namespace ThaoHR\Http\Controllers\Api\Auth;

use Illuminate\Auth\Events\Registered;
use ThaoHR\Http\Controllers\Api\ApiController;
use ThaoHR\Http\Requests\Auth\RegisterRequest;
use ThaoHR\Repositories\Role\RoleRepository;
use ThaoHR\Repositories\User\UserRepository;
use ThaoHR\Support\Enum\UserStatus;

class RegistrationController extends ApiController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * Create a new authentication controller instance.
     * @param UserRepository $userRepository
     * @param RoleRepository $roleRepository
     */
    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository)
    {
        $this->middleware('registration');

        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(RegisterRequest $request)
    {
        // Get role base on url
        $routeName = $request->route()->getName();
        list($roleName) = array_pad(explode('.', $routeName), 1, null);
        $role = $this->roleRepository->findByName($roleName);
        $roleId = !empty($role) ? $role->id : 2;

        $user = $this->userRepository->create(
            array_merge($request->validFormData(), ['role_id' => $roleId])
        );

        event(new Registered($user));

        return $this->setStatusCode(201)
            ->respondWithArray([
                'requires_email_confirmation' => !! setting('reg_email_confirmation')
            ]);
    }

    /**
     * Verify email via email confirmation token.
     * @param $token
     * @return \Illuminate\Http\Response
     */
    public function verifyEmail($token)
    {
        if (! setting('reg_email_confirmation')) {
            return $this->errorNotFound();
        }

        if ($user = $this->userRepository->findByConfirmationToken($token)) {
            $this->userRepository->update($user->id, [
                'status' => UserStatus::ACTIVE,
                'confirmation_token' => null
            ]);

            return $this->respondWithSuccess();
        }

        return $this->setStatusCode(400)
            ->respondWithError("Invalid confirmation token.");
    }
}
