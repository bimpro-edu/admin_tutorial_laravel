<?php

namespace ThaoHR\Http\Controllers\Api\Profile;

use ThaoHR\Events\User\UpdatedProfileDetails;
use ThaoHR\Http\Controllers\Api\ApiController;
use ThaoHR\Http\Requests\User\UpdateProfileDetailsRequest;
use ThaoHR\Http\Requests\User\UpdateProfileLoginDetailsRequest;
use ThaoHR\Repositories\User\UserRepository;
use ThaoHR\Transformers\UserTransformer;

/**
 * Class DetailsController
 * @package ThaoHR\Http\Controllers\Api\Profile
 */
class AuthDetailsController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Updates user profile details.
     * @param UpdateProfileLoginDetailsRequest $request
     * @param UserRepository $users
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProfileLoginDetailsRequest $request, UserRepository $users)
    {
        $user = $request->user();

        $data = $request->only(['email', 'username', 'password']);

        $user = $users->update($user->id, $data);

        return $this->respondWithItem($user, new UserTransformer);
    }
}
