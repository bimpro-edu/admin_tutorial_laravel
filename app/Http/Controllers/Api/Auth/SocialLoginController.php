<?php

namespace ThaoHR\Http\Controllers\Api\Auth;

use Auth;
use Exception;
use Socialite;
use ThaoHR\Events\User\LoggedIn;
use ThaoHR\Http\Controllers\Api\ApiController;
use ThaoHR\Http\Requests\Auth\Social\ApiAuthenticateRequest;
use ThaoHR\Repositories\Role\RoleRepository;
use ThaoHR\Repositories\User\UserRepository;
use ThaoHR\Services\Auth\Social\SocialManager;

class SocialLoginController extends ApiController
{
    /**
     * @var UserRepository
     */
    private $users;

    /**
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * @var SocialManager
     */
    private $socialManager;

    /**
     * @param UserRepository $users
     * @param SocialManager $socialManager
     */
    public function __construct(UserRepository $users, SocialManager $socialManager, RoleRepository $roleRepository)
    {
        $this->users = $users;
        $this->socialManager = $socialManager;
        $this->roleRepository = $roleRepository;
    }

    public function index(ApiAuthenticateRequest $request)
    {
        try {
            $socialUser = Socialite::driver($request->network)->userFromToken($request->social_token);
        } catch (Exception $e) {
            return $this->respondWithArray([
                'meta' => [
                    'status' => 401,
                    'message' => 'Could not connect to specified social network.'
                ],
                "data" => NULL
            ]);
        }
        if (empty($socialUser->email)) {
            $socialUser->email = $socialUser->id.'@facebook.com';
        }

        $user = $this->users->findBySocialId(
            $request->network,
            $socialUser->getId()
        );

        if (! $user) {
            if (! setting('reg_enabled')) {
                return $this->respondWithArray([
                    'meta' => [
                        'status' => 401,
                        'message' => 'Only users who already created an account can log in.'
                    ],
                    "data" => NULL
                ]);
            }

            $user = $this->socialManager->associate($socialUser, $request->network);
        }

        if (!$this->roleRepository->isValidLoginRole($request->route()->getName(), $user->role_id)) {
            return $this->respondWithArray([
                'meta' => [
                    'status' => 401,
                    'message' => 'Your account is invalid.'
                ],
                "data" => NULL
            ]);
        }

        if ($user->isBanned()) {
            return $this->respondWithArray([
                'meta' => [
                    'status' => 401,
                    'message' => 'Your account is banned by administrators.'
                ],
                "data" => NULL
            ]);
        }

        $token = Auth::guard('api')->login($user);

        event(new LoggedIn);

        return $this->respondWithArray([
            'meta' => [
                'status' => 200,
                'message' => 'Successful'
            ],
            'data' => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'profile_picture' => $user->avatar,
                'token' => $token
            ]
        ]);
    }
}
