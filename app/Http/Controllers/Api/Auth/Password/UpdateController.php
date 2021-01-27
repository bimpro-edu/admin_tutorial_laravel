<?php

namespace ThaoHR\Http\Controllers\Api\Auth\Password;

use Auth;
use Password;
use ThaoHR\Http\Controllers\Api\ApiController;
use ThaoHR\Http\Requests\Auth\PasswordUpdateRequest;
use ThaoHR\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Hash;

class UpdateController extends ApiController
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * Create a new password controller instance.
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('auth');

        $this->userRepository = $userRepository;
    }

    /**
     * Change password
     *
     * @param PasswordUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(PasswordUpdateRequest $request)
    {
        $user = $request->user();
        $fields = $request->getPasswordUpdateFields();

        if (!(Hash::check($fields['password_old'], $user->password))) {
            return $this->respondWithArray([
                'meta' => [
                    'status' => 400,
                    'message' => 'Old password does not match.'
                ],
                "data" => "Old password does not match."
            ]);
        }

        //Change Password
        $this->userRepository->update($user->id, [
            'password' => $fields['password']
        ]);

        return $this->respondWithArray([
            'meta' => [
                'status' => 200,
                'message' => 'Update password successful.'
            ],
            "data" => "Update password successful."
        ]);
    }
}
