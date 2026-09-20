<?php

namespace Tests\Unit;

use App\Models\Newsletter;
use App\Models\User;
use Tests\TestCase;

class NewsletterAudienceTest extends TestCase
{
    public function test_audience_label_defaults_to_all(): void
    {
        $newsletter = new Newsletter(['audience' => null]);

        $this->assertSame('همه کاربران', $newsletter->audienceLabel());
    }

    public function test_all_audience_is_visible_to_any_user(): void
    {
        $newsletter = new Newsletter(['audience' => Newsletter::AUDIENCE_ALL]);
        $user = new User(['is_admin' => false]);

        $this->assertTrue($newsletter->isVisibleTo($user));
    }

    public function test_admin_audience_is_only_visible_to_admins(): void
    {
        $newsletter = new Newsletter(['audience' => Newsletter::AUDIENCE_ADMINS]);

        $this->assertTrue($newsletter->isVisibleTo(new User(['is_admin' => true])));
        $this->assertFalse($newsletter->isVisibleTo(new User(['is_admin' => false])));
    }
}
