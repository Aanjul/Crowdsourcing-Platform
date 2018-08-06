<?php

namespace App\BusinessLogicLayer;

use App\Models\User;
use App\Models\ViewModels\EditUser;
use App\Models\ViewModels\ManageUsers;
use App\Models\ViewModels\UserProfile;
use App\Repository\UserRepository;
use App\Repository\UserRoleRepository;
use App\Utils\MailChimpAdaptor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserManager
{
    private $userRepository;
    private $userRoleRepository;
    private $mailChimpManager;
    public static $USERS_PER_PAGE = 10;

    public function __construct(UserRepository $userRepository, UserRoleRepository $userRoleRepository, MailChimpAdaptor $mailChimpManager)
    {
        $this->userRepository = $userRepository;
        $this->userRoleRepository = $userRoleRepository;
        $this->mailChimpManager = $mailChimpManager;
    }

    function userIsPlatformAdmin($user)
    {
        return $this->userRepository->userIsPlatformAdmin($user);
    }

    public function getMyProfileData($user)
    {

        return new UserProfile($user);

    }

    public function getUser($userId)
    {
        return $this->userRepository->find($userId);
    }

    public function getManagePlatformUsersViewModel($paginationNumber, $filters = null)
    {
        $users = $this->userRepository->getPlatformUsers($paginationNumber, $filters, true);
        $allRoles = $this->userRoleRepository->getAllPlatformSpecificRoles();
        return new ManageUsers($users, $allRoles);
    }

    public function getEditUserViewModel($id)
    {
        $user = $this->userRepository->getUser($id);
        $userRoleIds = $user->roles->pluck('id');
        $allRoles = $this->userRoleRepository->getAllPlatformSpecificRoles();
        return new EditUser($user, $userRoleIds, $allRoles);
    }

    public function updateUserRoles($userId, $roleSelect)
    {
        $this->userRepository->updateUserRoles($userId, $roleSelect);
    }

    public function deactivateUser($id)
    {
        $user = $this->userRepository->getUserWithTrashed($id);
        $this->userRepository->softDeleteUser($user);
    }

    public function reactivateUser($id)
    {
        $user = $this->userRepository->getUserWithTrashed($id);
        $this->userRepository->reActivateUser($user);
    }

    public function getOrAddUserToPlatform($email, $nickname, $avatar, $password, $roleselect)
    {
        $emailCheck = $this->userRepository->getUserByEmail($email);
        // Check if email exists in db
        if ($emailCheck) {
            // If email exists, update roles
            $this->userRepository->updateUserRoles($emailCheck->id, $roleselect);
            return new ActionResponse(UserActionResponses::USER_UPDATED, $emailCheck);
        } else {
            // If user email does not exist in db, notify for registration.
            $user = User::create([
                'nickname' => $nickname,
                'email' => $email,
                'avatar' => $avatar,
                'password' => $password != null ? bcrypt($password) : null,
            ]);
            $user->save();
            $this->userRepository->updateUserRoles($user->id, $roleselect);
            return new ActionResponse(UserActionResponses::USER_CREATED, $user);
        }
    }

    /**
     * @param $data array the form data array
     * @throws HttpException
     */
    public function updateUser($data)
    {
        $user_id = Auth::User()->id;
        $obj_user = User::find($user_id);
        $obj_user->nickname = $data['nickname'];
        $current_password = $obj_user->password;
        if ($data['password'] && $current_password != null) {
            if (Hash::check($data['current_password'], $current_password)) {
                $obj_user->password = Hash::make($data['password']);
            } else {
                throw new HttpException(500, "Current Password Incorrect.");
            }
        }
        $obj_user->save();
    }


    public function getPlatformAdminUsersWithCriteria($paginationNum = null, $data)
    {
        return $this->userRepository->getPlatformUsers($paginationNum, $data, true);
    }

    public function handleSocialLoginUser($socialUser)
    {

        $result = $this->getOrAddUserToPlatform($socialUser->email,
            $socialUser->name,
            $socialUser->avatar,
            null,
            [UserRoles::REGISTERED_USER]);
        // write user to 'Registered Users' newsletter if logins for the first time
        if ($result->status == UserActionResponses::USER_CREATED)
            $this->mailChimpManager->subscribe($socialUser->email, 'registered_users');
        if ($result->status == UserActionResponses::USER_CREATED || UserActionResponses::USER_UPDATED) {
            $user = $result->data;
            auth()->login($user);
            return $user;
        } else {
            throw new \Exception($result->status);
        }
    }

    public function createUser(array $data)
    {
        $data['password'] = bcrypt($data['password']);
        return $this->userRepository->create($data);
    }
}
