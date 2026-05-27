@extends('layouts.main.main', ['title' => 'تایید تراکنش'])

@if($status && $transaction)
    @push('analytics-events')
        <script>
            gtag('event', 'purchase', {
                transaction_id: @json((string) $transaction->id),
                value: {{ (float) preg_replace('/[^\d.]/', '', (string) $transaction->price) }},
                currency: 'IRR'
            });
        </script>
    @endpush
@endif

@section('content')
    <div class="container p-5 my-0 my-lg-5">
        <div class="mt-5 w-100 text-center">
            @if($status)
                <div class="alert alert-success">
                    <p>
                        تراکنش
                        به شناسه<mark class="px-2">#{{ $transaction->id }}</mark>
                        با موفقیت انجام شد
                    </p>
                    @if($transaction && $transaction->reference)
                        <h4>کد پیگیری: {{ $transaction->reference }}</h4>
                    @endif
                    
                    @if($order)
                        <div class="mt-4">
                            <a href="{{ route('dashboard.rents.show', $order->id) }}" class="btn btn-lg" style="background-color: #D39D1A; border-color: #D39D1A; color: #000; font-weight: bold;">
                                پیگیری رزرو
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div class="alert alert-danger">
                    <p>
                        تراکنش
                        @if($transaction)
                            به شناسه <mark>#{{ $transaction->id }}</mark>
                        @endif
                        انجام نشد
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection
