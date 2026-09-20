<?php

namespace Tests\Unit;

use App\Models\SmsLog;
use App\Services\UserInboxService;
use App\Support\SmsTemplates;
use Tests\TestCase;

class UserInboxServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'sms_templates.login_otp.pattern_id' => 'otp-pattern',
            'sms_templates.order_created_renter.pattern_id' => 'order-renter-pattern',
            'sms_templates.order_created_admin.pattern_id' => 'order-admin-pattern',
            'sms_templates.after_residence.pattern_id' => 'after-residence-pattern',
            'sms_templates.home_submitted_host.pattern_id' => 'home-submitted-host-pattern',
        ]);
    }

    public function test_login_otp_is_hidden_from_inbox(): void
    {
        $this->assertFalse(SmsTemplates::isInboxVisible('otp-pattern'));
        $this->assertContains('otp-pattern', SmsTemplates::hiddenInboxPatternIds());
        $this->assertContains('کد ورود یکبارمصرف', SmsTemplates::hiddenInboxTitles());
        $this->assertContains('درخواست رزرو جدید — ادمین', SmsTemplates::hiddenInboxTitles());
    }

    public function test_order_and_survey_sms_are_visible_in_inbox(): void
    {
        $this->assertTrue(SmsTemplates::isInboxVisible('order-renter-pattern'));
        $this->assertTrue(SmsTemplates::isInboxVisible('after-residence-pattern'));
        $this->assertTrue(SmsTemplates::isInboxVisible('bulk'));
        $this->assertFalse(SmsTemplates::isInboxVisible('order-admin-pattern'));
    }

    public function test_sms_body_uses_bulk_message_text(): void
    {
        $smsLog = new SmsLog([
            'pattern_id' => 'bulk',
            'pattern_title' => 'پیامک گروهی',
            'parameters' => ['message' => 'سفارش شما ثبت شد'],
        ]);

        $this->assertSame('سفارش شما ثبت شد', UserInboxService::renderSmsBody($smsLog));
    }

    public function test_sms_body_replaces_template_placeholders(): void
    {
        $smsLog = new SmsLog([
            'pattern_id' => 'home-submitted-host-pattern',
            'pattern_title' => 'ثبت اقامتگاه — میزبان',
            'parameters' => ['HOST-NAME' => 'علی'],
        ]);

        $body = UserInboxService::renderSmsBody($smsLog);

        $this->assertStringContainsString('علی', $body);
        $this->assertStringNotContainsString('%HOST-NAME%', $body);
    }

    public function test_sms_body_uses_inbox_explanation_for_survey_message(): void
    {
        $smsLog = new SmsLog([
            'pattern_id' => 'after-residence-pattern',
            'pattern_title' => 'پس از پایان اقامت',
            'parameters' => ['ID' => '12'],
        ]);

        $body = UserInboxService::renderSmsBody($smsLog);

        $this->assertStringContainsString('سفر شما به پایان رسیده است', $body);
        $this->assertStringContainsString('ثبت نظر', $body);
    }

    public function test_name_value_parameters_are_normalized(): void
    {
        $normalized = UserInboxService::normalizeParameters([
            ['name' => 'HOME_NAME', 'value' => 'ویلا جنگلی'],
        ]);

        $this->assertSame('ویلا جنگلی', $normalized['HOME_NAME']);
    }

    public function test_lowercase_consultant_keys_are_mapped_for_inbox_body(): void
    {
        $smsLog = new SmsLog([
            'pattern_id' => 'order-renter-pattern',
            'pattern_title' => 'ثبت درخواست رزرو — مهمان',
            'parameters' => [
                ['name' => 'HOME_NAME', 'value' => 'ویلا جنگلی'],
                ['name' => 'consultant_name', 'value' => 'سحر خدائی'],
                ['name' => 'consultant_mobile', 'value' => '09991922317'],
            ],
        ]);

        $body = UserInboxService::renderSmsBody($smsLog);

        $this->assertStringContainsString('سحر خدائی', $body);
        $this->assertStringContainsString('09991922317', $body);
        $this->assertStringNotContainsString('مشاور شما — است', $body);
    }

    public function test_host_request_inbox_body_includes_order_link(): void
    {
        config(['sms_templates.order_created_owner.pattern_id' => 'order-owner-pattern']);

        $order = new \App\Models\Order();
        $order->id = 3967;
        $order->user_id = 12;
        $order->renter_id = 44;

        $smsLog = new SmsLog([
            'user_id' => 12,
            'pattern_id' => 'order-owner-pattern',
            'pattern_title' => 'درخواست رزرو جدید — میزبان',
            'parameters' => [
                ['name' => 'COUNT', 'value' => '4'],
                ['name' => 'START-DATE', 'value' => '1404/06/30'],
                ['name' => 'END-DATE', 'value' => '1404/06/31'],
                ['name' => 'AMOUNT', 'value' => '8,540,000'],
            ],
        ]);
        $smsLog->setRelation('related', $order);

        $body = UserInboxService::renderSmsBody($smsLog);

        $this->assertStringContainsString('برای تأیید یا رد درخواست', $body);
        $this->assertStringContainsString('/dashboard/orders/3967', $body);
    }
}
