@extends('layouts.main.main_mobile', ['title' => __('title.ticket') . ' #' . $ticket->id])

@section('content')
    @include('layouts.main.partials.navbar-mobile')
    
    <!-- Page Header -->
    <div class="container px-3 py-3">
        <div class="bg-white rounded-3 p-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h1 class="fw-bold mb-2" style="font-size: 18px; color: #333;">@lang('title.ticket') #{{ $ticket->id }}</h1>
            <p class="mb-0" style="font-size: 14px; color: #666;">{{ $ticket->title }}</p>
        </div>
    </div>

    <!-- Ticket Details -->
    <div class="container px-3 py-4">
        <!-- Status -->
        <div class="bg-white rounded-3 p-3 mb-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <div class="d-flex justify-content-between align-items-center">
                <span style="font-size: 14px; color: #666;">وضعیت:</span>
                <span class="badge bg-{{ $ticket->status('color') }}" style="font-size: 12px;">
                    {{ $ticket->status() }}
                </span>
            </div>
        </div>

        <!-- Messages -->
        <div class="bg-white rounded-3 p-3 mb-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h6 class="fw-bold mb-3" style="font-size: 16px; color: #333;">پیام‌ها</h6>
            
            @foreach($ticket->messages as $message)
                <div class="mb-3 p-3 {{ $message->sent_from === 'admin' ? 'bg-light border-start border-3 border-info' : 'bg-white border-start border-3 border-primary' }} rounded-end" style="box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge {{ $message->sent_from === 'admin' ? 'bg-info text-white' : 'bg-primary text-white' }}" style="font-size: 11px; font-weight: 500;">
                            {{ $message->sent_from === 'admin' ? 'پشتیبانی' : 'شما' }}
                        </span>
                        <small class="text-muted" style="font-size: 11px;">
                            {{ $message->created_at->format('Y/m/d H:i') }}
                        </small>
                    </div>
                    <p class="mb-0" style="font-size: 14px; line-height: 1.5; color: #333;">
                        {{ $message->content }}
                    </p>
                </div>
            @endforeach
        </div>

        <!-- Reply Form -->
        <div class="bg-white rounded-3 p-3 mb-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h6 class="fw-bold mb-3" style="font-size: 16px; color: #333;">ارسال پاسخ</h6>
            
            <form action="{{ route('dashboard.tickets.reply', $ticket) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <textarea name="message" class="form-control" rows="4" 
                              placeholder="پیام خود را بنویسید..." style="font-size: 14px;"></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100" style="background: #D39D1A; border-color: #D39D1A; font-size: 14px;">
                    <i class="bi bi-send me-1"></i>
                    ارسال پیام
                </button>
            </form>
        </div>

        <!-- Actions -->
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard.tickets.index') }}" class="btn btn-outline-secondary flex-fill" style="font-size: 14px;">
                <i class="bi bi-arrow-right me-1"></i>
                بازگشت
            </a>
        </div>
    </div>
@endsection




