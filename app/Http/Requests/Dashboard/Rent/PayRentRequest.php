<?php

namespace App\Http\Requests\Dashboard\Rent;

use App\Models\Order;
use App\Services\OrderShowPresenter;
use App\Services\PaymentGatewayService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PayRentRequest extends FormRequest
{
    private ?Order $payableOrder = null;

    public function authorize(): bool
    {
        $order = $this->payableOrder();

        return $order && app(OrderShowPresenter::class)->canRenterPay($order);
    }

    public function rules(): array
    {
        return [
            'gateway' => [
                'required',
                'string',
                Rule::in(array_keys(app(PaymentGatewayService::class)->availableForCheckout($this->user(), $this->payableOrder()))),
            ],
        ];
    }

    public function payableOrder(): ?Order
    {
        if ($this->payableOrder !== null) {
            return $this->payableOrder;
        }

        $this->payableOrder = $this->user()->rents()
            ->where('status', Order::AWAITING_PAYMENT)
            ->find($this->route('order'));

        return $this->payableOrder;
    }

    public function messages(): array
    {
        return [
            'gateway.required' => __('text.payment_gateway_required'),
            'gateway.in' => __('text.payment_gateway_unavailable'),
        ];
    }
}
