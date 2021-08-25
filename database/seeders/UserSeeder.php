<?php

namespace Database\Seeders;

use App\Http\Enums\UserRole;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createRoles();
        $this->createSuperAdmin();
    }
    private function createRoles()
    {
        foreach (UserRole::ROLES as $id => $roleEnum) {
             Role::findOrCreate($roleEnum);
        }
    }
    private function createSuperAdmin()
    {
        $user = User::create([
            'type' => 'ADMIN',
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'phone' => '0500000000',
            'password' => "secret",
            'remember_token' => Str::random(10),
        ]);
        $user->assignRole(UserRole::of(UserRole::ROLE_SUPER_ADMIN));
        $user->assignRole(UserRole::of(UserRole::ROLE_ADMIN));
    }
}
