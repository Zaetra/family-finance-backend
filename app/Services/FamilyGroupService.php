<?php

namespace App\Services;

use App\Models\FamilyGroup;
use App\Models\User;

class FamilyGroupService
{
    /**
     * Get family group details for a user.
     */
    public function getFamilyGroupForUser(User $user)
    {
        if ($user->family_group_id) {
            return $user->familyGroup->load('users');
        }
        return null;
    }

    /**
     * Create a new family group and associate it with the user.
     */
    public function createFamilyGroup(User $user, array $data)
    {
        $familyGroup = FamilyGroup::create($data);
        $user->update(['family_group_id' => $familyGroup->id]);
        return $familyGroup;
    }

    /**
     * Get family group details.
     */
    public function getDetails(FamilyGroup $familyGroup)
    {
        return $familyGroup->load(['users', 'transactions']);
    }

    /**
     * Update family group.
     */
    public function updateFamilyGroup(FamilyGroup $familyGroup, array $data)
    {
        $familyGroup->update($data);
        return $familyGroup;
    }

    /**
     * Delete family group.
     */
    public function deleteFamilyGroup(FamilyGroup $familyGroup)
    {
        $familyGroup->delete();
    }
}
