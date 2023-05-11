<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'package_create',
            ],
            [
                'id'    => 18,
                'title' => 'package_edit',
            ],
            [
                'id'    => 19,
                'title' => 'package_show',
            ],
            [
                'id'    => 20,
                'title' => 'package_delete',
            ],
            [
                'id'    => 21,
                'title' => 'package_access',
            ],
            [
                'id'    => 22,
                'title' => 'clinic_create',
            ],
            [
                'id'    => 23,
                'title' => 'clinic_edit',
            ],
            [
                'id'    => 24,
                'title' => 'clinic_show',
            ],
            [
                'id'    => 25,
                'title' => 'clinic_delete',
            ],
            [
                'id'    => 26,
                'title' => 'clinic_access',
            ],
            [
                'id'    => 27,
                'title' => 'doctor_create',
            ],
            [
                'id'    => 28,
                'title' => 'doctor_edit',
            ],
            [
                'id'    => 29,
                'title' => 'doctor_show',
            ],
            [
                'id'    => 30,
                'title' => 'doctor_delete',
            ],
            [
                'id'    => 31,
                'title' => 'doctor_access',
            ],
            [
                'id'    => 32,
                'title' => 'staff_create',
            ],
            [
                'id'    => 33,
                'title' => 'staff_edit',
            ],
            [
                'id'    => 34,
                'title' => 'staff_show',
            ],
            [
                'id'    => 35,
                'title' => 'staff_delete',
            ],
            [
                'id'    => 36,
                'title' => 'staff_access',
            ],
            [
                'id'    => 37,
                'title' => 'content_management_access',
            ],
            [
                'id'    => 38,
                'title' => 'content_category_create',
            ],
            [
                'id'    => 39,
                'title' => 'content_category_edit',
            ],
            [
                'id'    => 40,
                'title' => 'content_category_show',
            ],
            [
                'id'    => 41,
                'title' => 'content_category_delete',
            ],
            [
                'id'    => 42,
                'title' => 'content_category_access',
            ],
            [
                'id'    => 43,
                'title' => 'content_tag_create',
            ],
            [
                'id'    => 44,
                'title' => 'content_tag_edit',
            ],
            [
                'id'    => 45,
                'title' => 'content_tag_show',
            ],
            [
                'id'    => 46,
                'title' => 'content_tag_delete',
            ],
            [
                'id'    => 47,
                'title' => 'content_tag_access',
            ],
            [
                'id'    => 48,
                'title' => 'content_page_create',
            ],
            [
                'id'    => 49,
                'title' => 'content_page_edit',
            ],
            [
                'id'    => 50,
                'title' => 'content_page_show',
            ],
            [
                'id'    => 51,
                'title' => 'content_page_delete',
            ],
            [
                'id'    => 52,
                'title' => 'content_page_access',
            ],
            [
                'id'    => 53,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
