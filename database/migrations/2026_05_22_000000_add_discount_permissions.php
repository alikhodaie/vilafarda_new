<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    private array $permissions = [
        ['name' => 'discounts:index', 'fa_name' => 'مشاهده', 'group' => 'discounts', 'fa_group' => 'تخفیف‌ها', 'guard_name' => 'web'],
        ['name' => 'discounts:create', 'fa_name' => 'ایجاد', 'group' => 'discounts', 'fa_group' => 'تخفیف‌ها', 'guard_name' => 'web'],
        ['name' => 'discounts:update', 'fa_name' => 'ویرایش', 'group' => 'discounts', 'fa_group' => 'تخفیف‌ها', 'guard_name' => 'web'],
        ['name' => 'discounts:destroy', 'fa_name' => 'حذف', 'group' => 'discounts', 'fa_group' => 'تخفیف‌ها', 'guard_name' => 'web'],
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

        $names = collect($this->permissions)->pluck('name')->all();

        Permission::query()->whereIn('name', $names)->where('guard_name', 'web')->delete();
    }
};
