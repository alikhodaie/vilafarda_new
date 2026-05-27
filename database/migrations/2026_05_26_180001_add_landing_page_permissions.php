<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    private array $permissions = [
        ['name' => 'landing-pages:index', 'fa_name' => 'نمایش', 'group' => 'landing-pages', 'fa_group' => 'صفحات لندینگ', 'guard_name' => 'web'],
        ['name' => 'landing-pages:create', 'fa_name' => 'ایجاد', 'group' => 'landing-pages', 'fa_group' => 'صفحات لندینگ', 'guard_name' => 'web'],
        ['name' => 'landing-pages:update', 'fa_name' => 'ویرایش', 'group' => 'landing-pages', 'fa_group' => 'صفحات لندینگ', 'guard_name' => 'web'],
        ['name' => 'landing-pages:destroy', 'fa_name' => 'حذف', 'group' => 'landing-pages', 'fa_group' => 'صفحات لندینگ', 'guard_name' => 'web'],
    ];

    public function up(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $created = collect();

        foreach ($this->permissions as $attributes) {
            $permission = Permission::query()->firstOrCreate(
                ['name' => $attributes['name'], 'guard_name' => $attributes['guard_name']],
                $attributes
            );
            $created->push($permission);
        }

        $superAdmin = Role::query()->where('name', 'super-admin')->first();

        if ($superAdmin) {
            $superAdmin->givePermissionTo($created->pluck('name')->all());
        }
    }

    public function down(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::query()
            ->whereIn('name', collect($this->permissions)->pluck('name')->all())
            ->where('guard_name', 'web')
            ->delete();
    }
};
