<?php

namespace Tests\Unit;

use App\Models\Home;
use App\Models\Order;
use Tests\TestCase;

class HomeDeactivatedStatusTest extends TestCase
{
    public function test_deactivated_status_is_available_for_admin(): void
    {
        $this->assertArrayHasKey(Home::DEACTIVATED, Home::STATUSES);
        $this->assertSame('غیرفعال سازی', Home::STATUSES[Home::DEACTIVATED]['fa_text']);
    }

    public function test_deactivated_home_is_viewable_but_not_bookable(): void
    {
        $home = new Home([
            'status' => Home::DEACTIVATED,
            'is_draft' => false,
            'is_host_active' => true,
        ]);

        $this->assertTrue($home->isDeactivated());
        $this->assertTrue($home->isPubliclyViewable());
        $this->assertFalse($home->isBookingEnabled());
    }

    public function test_accepted_host_active_home_is_bookable(): void
    {
        $home = new Home([
            'status' => Home::ACCEPTED,
            'is_draft' => false,
            'is_host_active' => true,
        ]);

        $this->assertFalse($home->isDeactivated());
        $this->assertTrue($home->isPubliclyViewable());
        $this->assertTrue($home->isBookingEnabled());
    }

    public function test_host_inactive_home_is_viewable_but_not_bookable(): void
    {
        $home = new Home([
            'status' => Home::ACCEPTED,
            'is_draft' => false,
            'is_host_active' => false,
        ]);

        $this->assertFalse($home->isDeactivated());
        $this->assertTrue($home->isPubliclyViewable());
        $this->assertFalse($home->isBookingEnabled());
        $this->assertTrue($home->disable_dates->contains(Order::getMinReserveDate()->format('Y/m/d')));
    }

    public function test_deactivated_calendar_closes_all_reserve_days(): void
    {
        $home = new Home([
            'status' => Home::DEACTIVATED,
            'is_draft' => false,
        ]);

        $dates = $home->disable_dates;
        $min = Order::getMinReserveDate()->format('Y/m/d');
        $max = Order::getMaxReserveDate()->format('Y/m/d');

        $this->assertTrue($dates->contains($min));
        $this->assertTrue($dates->contains($max));
        $this->assertGreaterThan(60, $dates->count());
    }
}
