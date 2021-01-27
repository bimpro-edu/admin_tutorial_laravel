<?php

namespace ThaoHR\Http\Controllers\Web\Profile;

use ThaoHR\Events\User\UpdatedProfileDetails;
use ThaoHR\Http\Controllers\Controller;
use ThaoHR\Http\Requests\User\UpdateProfileDetailsRequest;
use ThaoHR\Repositories\User\UserRepository;

/**
 * Class DetailsController
 * @package ThaoHR\Http\Controllers
 */
class DetailsController extends Controller
{
    /**
     * @var UserRepository
     */
    private $users;

    /**
     * DetailsController constructor.
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * Update profile details.
     *
     * @param UpdateProfileDetailsRequest $request
     * @return mixed
     */
    public function update(UpdateProfileDetailsRequest $request)
    {
        $this->users->update(auth()->id(), $request->except('role_id', 'status'));

        event(new UpdatedProfileDetails);

        return redirect()->back()
            ->withSuccess(__('Profile updated successfully.'));
    }
}
