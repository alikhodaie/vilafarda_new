<?php

namespace Tests\Unit;

use App\Models\Home;
use App\Support\SmsTemplates;
use Tests\TestCase;

class HomeSubmittedSmsTest extends TestCase
{
    public function test_host_is_notified_when_draft_is_submitted_for_review(): void
    {
        $home = $this->homeWithOriginal([
            'is_draft' => true,
            'status' => Home::PENDING,
        ]);
        $home->is_draft = false;
        $home->syncChanges();

        $this->assertTrue($home->shouldNotifyHostOnSubmit());
    }

    public function test_host_is_not_notified_when_accepted_home_returns_to_review(): void
    {
        $home = $this->homeWithOriginal([
            'is_draft' => false,
            'status' => Home::ACCEPTED,
        ]);
        $home->status = Home::PENDING;
        $home->syncChanges();

        $this->assertFalse($home->shouldNotifyHostOnSubmit());
    }

    public function test_host_is_not_notified_when_unrelated_fields_change(): void
    {
        $home = $this->homeWithOriginal([
            'is_draft' => false,
            'status' => Home::PENDING,
            'name' => 'ویلا',
        ]);
        $home->name = 'ویلای جدید';
        $home->syncChanges();

        $this->assertFalse($home->shouldNotifyHostOnSubmit());
    }

    public function test_sms_templates_include_home_submitted_pattern(): void
    {
        $template = SmsTemplates::all()->firstWhere('key', 'home_submitted_host');

        $this->assertNotNull($template);
        $this->assertSame('homes', $template['category']);
        $this->assertSame(['HOST-NAME'], $template['parameters']);
        $this->assertSame('HomeObserver::updated', $template['source']);
    }

    public function test_host_is_notified_when_pending_home_is_accepted(): void
    {
        $home = $this->homeWithOriginal([
            'is_draft' => false,
            'status' => Home::PENDING,
        ]);
        $home->status = Home::ACCEPTED;
        $home->syncChanges();

        $this->assertTrue($home->shouldNotifyHostOnReview());
        $this->assertSame('تایید شد', $home->reviewResultLabel());
    }

    public function test_host_is_notified_when_pending_home_is_rejected(): void
    {
        $home = $this->homeWithOriginal([
            'is_draft' => false,
            'status' => Home::PENDING,
        ]);
        $home->status = Home::REJECTED;
        $home->syncChanges();

        $this->assertTrue($home->shouldNotifyHostOnReview());
        $this->assertSame('رد شد', $home->reviewResultLabel());
    }

    public function test_host_is_not_notified_when_accepted_home_is_rejected(): void
    {
        $home = $this->homeWithOriginal([
            'is_draft' => false,
            'status' => Home::ACCEPTED,
        ]);
        $home->status = Home::REJECTED;
        $home->syncChanges();

        $this->assertFalse($home->shouldNotifyHostOnReview());
    }

    public function test_sms_templates_include_home_reviewed_pattern(): void
    {
        $template = SmsTemplates::all()->firstWhere('key', 'home_reviewed_host');

        $this->assertNotNull($template);
        $this->assertSame('homes', $template['category']);
        $this->assertSame(['HOST-NAME', 'REVIEW-RESULT'], $template['parameters']);
        $this->assertSame('HomeObserver::updated', $template['source']);
    }

    public function test_sms_templates_include_home_submitted_admin_pattern(): void
    {
        $template = SmsTemplates::all()->firstWhere('key', 'home_submitted_admin');

        $this->assertNotNull($template);
        $this->assertSame('homes', $template['category']);
        $this->assertSame(['ADMIN-NAME'], $template['parameters']);
        $this->assertSame('ادمین (دریافت پیامک همیشگی و نوبتی)', $template['recipient']);
        $this->assertSame('HomeObserver::updated', $template['source']);
    }

    private function homeWithOriginal(array $attributes): Home
    {
        $home = new Home();
        $home->exists = true;
        $home->setRawAttributes($attributes, true);

        return $home;
    }
}
