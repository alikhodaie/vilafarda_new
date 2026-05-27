<?php

namespace App\Policies\Category;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization,
        ArticleCategoryPolicy, HomeCategoryPolicy, FAQCategoryPolicy;
}
