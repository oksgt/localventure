<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $superadmin = Role::create(['id' => 1, 'name' => 'superadmin']);
        $admin = Role::create(['id' => 2, 'name' => 'administrator']);
        $operator = Role::create(['id' => 3, 'name' => 'operator']);

        // Create Permissions
        $createPost = Permission::create(['name' => 'create-post']);
        $editPost = Permission::create(['name' => 'edit-post']);
        $deletePost = Permission::create(['name' => 'delete-post']);

        // Assign Permissions to Roles
        $superadmin->permissions()->attach([$createPost->id, $editPost->id, $deletePost->id]);
        $admin->permissions()->attach([$createPost->id, $editPost->id]);
        $operator->permissions()->attach([$createPost->id]);
    }
}
