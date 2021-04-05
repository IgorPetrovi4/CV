<?php

namespace App\Services\AmoCRM;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\AccountSettings\UsersGroup;
use AmoCRM\Models\UserModel;
use App\Models\AmoCRM\AmoUserGroup;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Users
{
    private $apiClient;

    public function __construct(AmoCRMApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function sync($user_id)
    {
        try {
            $user = $this->apiClient->users()->getOne($user_id);
            $this->createOrUpdate($user);
            return $user;
        } catch (AmoCRMApiException $e) {
            throw $e;
            die;
        }
    }

    public function syncAll()
    {
        try {
            $usersCollection = $this->apiClient->users()->get(null, [UserModel::GROUP]);
            foreach ($usersCollection as $user) {
                $this->createOrUpdate($user);
                return $user;
            }
        } catch (AmoCRMApiException $e) {
            throw $e;
            die;
        }
    }

    public function createOrUpdate(UserModel $user)
    {
        $amo_user = User::updateOrCreate(['amo_user_id' => $user->getId()], [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => Hash::make(Str::random(10)),
            'amo_user_id' => $user->getId(),
            'amo_account_id' => auth()->user()->amo_account->id,

        ]);

        $group = $user->getGroups()->first();
        if (!empty($group)) {
            $this->createOrUpdateGroup($group);
            $amo_user->amo_group_id = $group->getId();
            $amo_user->save();
        }

        return $amo_user;
    }

    public function createOrUpdateGroup(UsersGroup $group)
    {
        $amoUserGroup = AmoUserGroup::findOrNew($group->getId());
        $amoUserGroup->id = $group->getId();
        $amoUserGroup->name = $group->getName();
        $amoUserGroup->save();

        return $amoUserGroup;
    }
}


