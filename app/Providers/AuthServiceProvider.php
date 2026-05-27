<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Consultant;
use App\Models\Contact;
use App\Models\Discount;
use App\Models\FAQ;
use App\Models\Health;
use App\Models\Home;
use App\Models\Newsletter;
use App\Models\NewsletterSubscriber;
use App\Models\Option;
use App\Models\Navbar;
use App\Models\Order;
use App\Models\Safety;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use App\Models\Variable;
use App\Models\HostPayout;
use App\Models\LandingPage;
use App\Models\Withdraw;
use App\Policies\Category\CategoryPolicy;
use App\Policies\CommentPolicy;
use App\Policies\ConsultantPolicy;
use App\Policies\ContactPolicy;
use App\Policies\DiscountPolicy;
use App\Policies\FAQPolicy;
use App\Policies\HealthPolicy;
use App\Policies\HomeOptionPolicy;
use App\Policies\HomePolicy;
use App\Policies\NavbarPolicy;
use App\Policies\NewsletterPolicy;
use App\Policies\NewsletterSubscriberPolicy;
use App\Policies\OrderPolicy;
use App\Policies\RolePolicy;
use App\Policies\SafetyPolicy;
use App\Policies\SettingPolicy;
use App\Policies\TicketMessagePolicy;
use App\Policies\TicketPolicy;
use App\Policies\User\UserPolicy;
use App\Policies\VariablePolicy;
use App\Policies\HostPayoutPolicy;
use App\Policies\LandingPagePolicy;
use App\Policies\SmsTemplatePolicy;
use App\Policies\WithdrawPolicy;
use App\Support\SmsTemplates;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Navbar::class => NavbarPolicy::class,
        Comment::class => CommentPolicy::class,
        Category::class => CategoryPolicy::class,
        Option::class => HomeOptionPolicy::class,
        Ticket::class => TicketPolicy::class,
        TicketMessage::class => TicketMessagePolicy::class,
        Setting::class => SettingPolicy::class,
        Contact::class => ContactPolicy::class,
        Home::class => HomePolicy::class,
        Variable::class => VariablePolicy::class,
        FAQ::class => FAQPolicy::class,
        Newsletter::class => NewsletterPolicy::class,
        NewsletterSubscriber::class => NewsletterSubscriberPolicy::class,
        Consultant::class => ConsultantPolicy::class,
        Order::class => OrderPolicy::class,
        Withdraw::class => WithdrawPolicy::class,
        HostPayout::class => HostPayoutPolicy::class,
        Health::class => HealthPolicy::class,
        Safety::class => SafetyPolicy::class,
        Discount::class => DiscountPolicy::class,
        LandingPage::class => LandingPagePolicy::class,
        SmsTemplates::class => SmsTemplatePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
