<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Webkul\Contact\Models\Person;

class CodeHelper
{

    public static function haveUserPermission($userId, $permissionName, $guard = 'web')
    {
        $role = DB::table('core_model_has_roles')
            ->where('model_id', $userId)
            ->where('model_type', 'App\User')
            ->first();
        if ($role != null) {
            $roleId = $role->role_id;
            $permission = DB::table('core_permissions')
                ->where('name', $permissionName)
                ->where('guard_name', $guard)
                ->first();
            if ($permission != null) {
                $permissionId = $permission->id;
                $roleHasPermission = DB::table('core_role_has_permissions')
                    ->where('permission_id', $permissionId)
                    ->where('role_id', $roleId)
                    ->first();
                if ($roleHasPermission != null) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function isAdmin($user)
    {
        if (self::haveUserPermission($user->id, 'dashboard_access')) {
            return true;
        }
        return false;
    }

    public static function isVendor($user)
    {
        if (self::haveUserPermission($user->id, 'dashboard_vendor_access')) {
            return true;
        }
        return false;
    }

    public static function createPerson($user, $personData)
    {
        $contactPerson = Person::where('user_id', $user->id)
            ->whereRaw(
                'JSON_CONTAINS(emails, ?)',
                [json_encode(['value' => $personData['email']])]
            )
            ->first();
        if ($contactPerson == null) {
            //create contact
            $contactPerson = new Person([
                "name" => $personData['name'],
                "emails" => [
                    [
                        "label" => "work",
                        "value" => $personData['email']
                    ]
                ],
                "contact_numbers" => [
                    [
                        "label" => "work",
                        "value" => $personData['mobile_no']
                    ]
                ],
                "user_id" => $user->id
            ]);
            if ($contactPerson->save()) {
                return self::createPerson($user, $personData);
            }
        } else {
            return $contactPerson;
        }
        return null;
    }

    public static function timeAgo($timestamp) {
        $time_ago = strtotime($timestamp); // Convert timestamp to Unix timestamp
        $current_time = time(); // Get current Unix timestamp
        $time_difference = $current_time - $time_ago; // Difference between the current time and the timestamp
    
        // Define time periods
        $seconds = $time_difference;
        $minutes      = round($seconds / 60);           // value 60 is seconds
        $hours        = round($seconds / 3600);         // value 3600 is 60 minutes * 60 sec
        $days         = round($seconds / 86400);        // value 86400 is 24 hours * 60 minutes * 60 sec
        $weeks        = round($seconds / 604800);       // value 604800 is 7 days * 24 hours * 60 minutes * 60 sec
        $months       = round($seconds / 2629440);      // value 2629440 is ((365+365+365+365+365)/5/12/30)
        $years        = round($seconds / 31553280);     // value 31553280 is 365.25 days * 24 hours * 60 minutes * 60 sec
    
        // Now we calculate the time ago string based on the time difference
        if ($seconds <= 60) {
            return "Just Now";
        } else if ($minutes <= 60) {
            if ($minutes == 1) {
                return "one minute ago";
            } else {
                return "$minutes minutes ago";
            }
        } else if ($hours <= 24) {
            if ($hours == 1) {
                return "an hour ago";
            } else {
                return "$hours hours ago";
            }
        } else if ($days <= 7) {
            if ($days == 1) {
                return "yesterday";
            } else {
                return "$days days ago";
            }
        } else if ($weeks <= 4.3) { // 4.3 == 30/7
            if ($weeks == 1) {
                return "a week ago";
            } else {
                return "$weeks weeks ago";
            }
        } else if ($months <= 12) {
            if ($months == 1) {
                return "a month ago";
            } else {
                return "$months months ago";
            }
        } else {
            if ($years == 1) {
                return "one year ago";
            } else {
                return "$years years ago";
            }
        }
    }
    

}