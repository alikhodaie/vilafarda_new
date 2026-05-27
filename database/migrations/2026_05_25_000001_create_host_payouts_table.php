<?php

use App\Models\HostPayout;
use App\Models\Order;
use App\Services\HostPayoutService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostPayoutsTable extends Migration
{
    public function up()
    {
        Schema::create('host_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('order_price');
            $table->unsignedInteger('commission_percent')->default(0);
            $table->unsignedBigInteger('commission_amount')->default(0);
            $table->enum('status', array_keys(HostPayout::STATUSES))->default(HostPayout::PENDING);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        $service = app(HostPayoutService::class);

        Order::query()
            ->whereNotNull('paid_at')
            ->with('home')
            ->chunkById(100, function ($orders) use ($service) {
                foreach ($orders as $order) {
                    if (! $order->home) {
                        continue;
                    }

                    if (in_array($order->status, [Order::CANCELED, Order::REJECTED], true)) {
                        $service->syncFromOrder($order, HostPayout::CANCELLED);

                        continue;
                    }

                    if (in_array($order->status, [
                        Order::WAITING_FOR_RENTER,
                        Order::IN_RENT,
                        Order::DONE,
                    ], true)) {
                        $service->syncFromOrder($order, HostPayout::PENDING);
                    }
                }
            });
    }

    public function down()
    {
        Schema::dropIfExists('host_payouts');
    }
}
