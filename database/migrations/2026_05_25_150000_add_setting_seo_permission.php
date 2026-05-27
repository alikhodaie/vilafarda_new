<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    private array $permission = [
        'name' => 'setting:seo',
        'fa_name' => 'سئو و متا تگ‌ها',
        'group' => 'settings',
        'fa_group' => 'تنظیمات',
        'guard_name' => 'web',
    ];

    public function up(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permission = Permission::query()->firstOrCreate(
            ['name' => $this->permission['name'], 'guard_name' => $this->permission['guard_name']],
            $this->permission
        );

        $superAdmin = Role::query()->where('name', 'super-admin')->first();
        if ($superAdmin && ! $superAdmin->hasPermissionTo($permission->name)) {
            $superAdmin->givePermissionTo($permission->name);
        }

        Role::query()
            ->whereHas('permissions', function ($query) {
                $query->where('name', 'setting:index')->where('guard_name', 'web');
            })
            ->each(function (Role $role) use ($permission) {
                if (! $role->hasPermissionTo($permission->name)) {
                    $role->givePermissionTo($permission->name);
                }
            });
    }

    public function down(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::query()
            ->where('name', $this->permission['name'])
            ->where('guard_name', $this->permission['guard_name'])
            ->delete();
    }
};
