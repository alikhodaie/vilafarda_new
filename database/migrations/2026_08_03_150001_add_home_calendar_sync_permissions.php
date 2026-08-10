<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    private array $permissions = [
        ['name' => 'home-calendar-sync:index', 'fa_name' => 'مشاهده به‌روزرسانی تقویم', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
        ['name' => 'home-calendar-sync:update', 'fa_name' => 'ویرایش به‌روزرسانی تقویم', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
        ['name' => 'home-calendar-sync:sync', 'fa_name' => 'اجرای همگام‌سازی تقویم', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
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
