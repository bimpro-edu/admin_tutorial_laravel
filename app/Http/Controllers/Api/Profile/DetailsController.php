<?php

namespace ThaoHR\Http\Controllers\Api\Profile;

use ThaoHR\Events\User\UpdatedProfileDetails;
use ThaoHR\Http\Controllers\Api\ApiController;
use ThaoHR\Http\Requests\User\UpdateProfileDetailsRequest;
use ThaoHR\Repositories\User\UserRepository;
use ThaoHR\Transformers\UserTransformer;
use ThaoHR\Http\Requests\User\UpdateLocationRequest;

/**
 * Class DetailsController
 * @package ThaoHR\Http\Controllers\Api\Profile
 */
class DetailsController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Handle user details request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->respondWithItem(
            auth()->user(),
            new UserTransformer
        );
    }

    /**
     * Updates user profile details.
     * @param UpdateProfileDetailsRequest $request
     * @param UserRepository $users
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProfileDetailsRequest $request, UserRepository $users)
    {
        $user = $request->user();

        $data = collect($request->all());

        $data = $data->only([
            'first_name', 'last_name', 'birthday',
            'phone', 'address', 'country_id', 'lat','long'
        ])->toArray();

        if (! isset($data['country_id'])) {
            $data['country_id'] = $user->country_id;
        }

        $user = $users->update($user->id, $data);

        event(new UpdatedProfileDetails);

        return $this->respondWithItem($user, new UserTransformer);
    }
    
    /**
     * @param UpdateLocationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLocation(UpdateLocationRequest $request, UserRepository $userRepository)
    {
        $currentUser = $request->user();
        $data = collect($request->all());
        $data = $data->only([
             'lat','long'
        ])->toArray();
        $userRepository->update($currentUser->id, $data);
        return $this->respondWithArray([
            'meta' => [
                'status' => 200,
                'message' => 'Successful.'
            ],
            "data" => 'Updated location'
        ]);
    }
}
