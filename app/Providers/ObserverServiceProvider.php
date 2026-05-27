<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Consultant;
use App\Models\FAQ;
use App\Models\Health;
use App\Models\Home;
use App\Models\Navbar;
use App\Models\Option;
use App\Models\Order;
use App\Models\Safety;
use App\Models\Setting;
use App\Models\User;
use App\Models\Variable;
use App\Observers\CommentObserver;
use App\Observers\ConsultantObserver;
use App\Observers\FAQObserver;
use App\Observers\HealthObserver;
use App\Observers\HomeObserver;
use App\Observers\NavbarObserver;
use App\Observers\OptionObserver;
use App\Observers\OrderObserver;
use App\Observers\SafetyObserver;
use App\Observers\SettingObserver;
use App\Observers\UserObserver;
use App\Observers\VariableObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Navbar::observe(NavbarObserver::class);
        Comment::observe(CommentObserver::class);
        Setting::observe(SettingObserver::class);
        Option::observe(OptionObserver::class);
        Home::observe(HomeObserver::class);
        Variable::observe(VariableObserver::class);
        FAQ::observe(FAQObserver::class);
        Consultant::observe(ConsultantObserver::class);
        Order::observe(OrderObserver::class);
        Health::observe(HealthObserver::class);
        Safety::observe(SafetyObserver::class);
    }
}
