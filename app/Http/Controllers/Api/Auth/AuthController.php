<?php

namespace ThaoHR\Http\Controllers\Api\Auth;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use ThaoHR\Events\User\LoggedIn;
use ThaoHR\Events\User\LoggedOut;
use ThaoHR\Http\Controllers\Api\ApiController;
use ThaoHR\Http\Requests\Auth\LoginRequest;
use ThaoHR\Repositories\Role\RoleRepository;
use ThaoHR\Repositories\User\UserRepository;

/**
 * Class LoginController
 * @package ThaoHR\Http\Controllers\Api\Auth
 */
class AuthController extends ApiController
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
        $this->middleware('guest')->only('login');
        $this->middleware('auth')->only('logout');
        
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Attempt to log the user in and generate unique
     * JWT token on successful authentication.
     *
     * @param LoginRequest $request
     * @return JsonResponse|Response
     * @throws BindingResolutionException
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->getCredentials();

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->errorUnauthorized('Invalid credentials.');
            }
        } catch (JWTException $e) {
            return $this->errorInternalError('Could not create token.');
        }

        // Get role base on url
        $user = auth()->user();

        if (!$this->roleRepository->isValidLoginRole($request->route()->getName(), $user->role_id)) {
            $this->invalidateToken($token);
            return $this->errorUnauthorized('Your account is invalid.');
        }

        if ($user->isBanned()) {
            $this->invalidateToken($token);
            return $this->errorUnauthorized('Your account is banned by administrators.');
        }

        if ($user->isUnconfirmed()) {
            $this->invalidateToken($token);
            return $this->errorUnauthorized('Please confirm your email address first.');
        }

        $fcmToken = $request->get('fcm_token');
        if (!empty($fcmToken) && $fcmToken != $user->fcm_token) {
            $user->update(['fcm_token' => $fcmToken]);
        }

        event(new LoggedIn);

        return $this->respondWithArray(compact('token'));
    }

    private function invalidateToken($token)
    {
        JWTAuth::setToken($token);
        JWTAuth::invalidate();
    }

    /**
     * Logout user and invalidate token.
     * @return JsonResponse
     */
    public function logout()
    {
        event(new LoggedOut);

        auth()->logout();

        return $this->respondWithSuccess();
    }
}
