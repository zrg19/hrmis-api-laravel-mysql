<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'Admin']);
        $managerRole = Role::create(['name' => 'Manager']);
        $employeeRole = Role::create(['name' => 'Employee']);

        // Create permissions
        $permissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'view leaves',
            'create leaves',
            'edit leaves',
            'delete leaves',
            'approve leaves',
            'view tasks',
            'create tasks',
            'edit tasks',
            'delete tasks',
            'assign tasks',
            'view customer measurements',
            'create customer measurements',
            'edit customer measurements',
            'delete customer measurements',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all());
        
        $managerRole->givePermissionTo([
            'view users',
            'view leaves',
            'approve leaves',
            'view tasks',
            'create tasks',
            'edit tasks',
            'assign tasks',
            'view customer measurements',
            'create customer measurements',
            'edit customer measurements',
            'delete customer measurements',
        ]);
        
        $employeeRole->givePermissionTo([
            'view leaves',
            'create leaves',
            'edit leaves',
            'view tasks',
        ]);

        // Create a default admin user
        $adminUser = \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@hrmis.com',
            'password' => bcrypt('password123'),
            'department' => 'Administration',
            'designation' => 'System Administrator',
            'phone' => '1234567890',
            'address' => 'System Administration Office',
            'role' => 'Admin',
            'is_active' => true,
        ]);

        $adminUser->assignRole('Admin');
    }
}
