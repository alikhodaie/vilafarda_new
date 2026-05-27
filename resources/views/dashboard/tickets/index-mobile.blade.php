@extends('layouts.main.main_mobile', ['title' => __('title.tickets')])

@section('content')
    @include('layouts.main.partials.navbar-mobile')
    
    <!-- Page Header -->
    <div class="container px-3 py-3">
        <div class="bg-white rounded-3 p-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h1 class="fw-bold mb-0" style="font-size: 18px; color: #333;">@lang('title.tickets')</h1>
                <a href="{{ route('dashboard.tickets.create') }}" class="btn btn-mobile-primary" style="font-size: 12px; padding: 8px 16px;">
                    <i class="bi bi-plus-circle me-1"></i>
                    تیکت جدید
                </a>
            </div>
            <p class="mb-0" style="font-size: 14px; color: #666;">تیکت‌های پشتیبانی</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="container px-3 py-3">
        <div class="row g-2">
            <div class="col-8">
                <button class="btn btn-mobile-secondary w-100" data-bs-toggle="modal" data-bs-target="#filterModal" style="font-size: 14px;">
                    <i class="bi bi-funnel me-1"></i>
                    فیلتر و جستجو
                </button>
            </div>
            <div class="col-4">
                <a href="{{ route('dashboard.tickets.create') }}" class="btn btn-mobile-primary w-100" style="font-size: 14px;">
                    <i class="bi bi-plus-circle me-1"></i>
                    تیکت جدید
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel" style="font-size: 16px;">فیلتر و جستجو</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('dashboard.tickets.index') }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 14px;">@lang('title.search')</label>
                            <input type="text" class="form-control" placeholder="@lang('title.search')" 
                                   name="title" value="{{ request('title') }}" style="font-size: 14px;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-size: 14px;">@lang('title.status')</label>
                            <select class="form-control" id="sort_status" name="status" style="font-size: 14px;">
                                <option value="">@lang('title.status')</option>
                                @foreach(\App\Models\Ticket::STATUS as $status)
                                    <option value="{{ $status['value'] }}" @if($status['value'] === request('status')) selected @endif>
                                        {{ $status['fa_text'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 14px; border-radius: 12px;">انصراف</button>
                        <button type="submit" class="btn btn-primary" style="background: #D39D1A; border-color: #D39D1A; color: white; font-size: 14px; border-radius: 12px;">
                            <i class="bi bi-search me-1"></i>
                            اعمال فیلتر
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tickets List -->
    <div class="container px-3 pb-4">
        @if($tickets->isNotEmpty())
            @foreach($tickets as $ticket)
                <div class="bg-white rounded-3 p-3 mb-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="fw-bold mb-0" style="font-size: 16px;">{{ $ticket->title }}</h5>
                        <span class="badge btn-{{ $ticket->status('color') }}" style="font-size: 11px;">
                            {{ $ticket->status() }}
                        </span>
                    </div>

                    <p class="text-muted mb-2" style="font-size: 14px;">
                        {{ Str::limit($ticket->messages->first()->message ?? '', 100) }}
                    </p>

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted" style="font-size: 12px;">
                            {{ $ticket->created_at->format('Y/m/d H:i') }}
                        </small>
                        <a href="{{ route('dashboard.tickets.show', $ticket->id) }}" 
                           class="btn btn-primary btn-sm" style="font-size: 12px; background: #D39D1A; border-color: #D39D1A; color: white; border-radius: 8px;">
                            <i class="bi bi-eye me-1"></i>
                            مشاهده
                        </a>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $tickets->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-ticket-detailed fs-1 text-muted mb-3"></i>
                <h5 class="text-muted">@lang('title.nothing found')</h5>
                <p class="text-muted" style="font-size: 14px;">
                    @if(request('title') || request('status'))
                        نتیجه‌ای برای فیلترهای انتخابی پیدا نشد
                    @else
                        هنوز هیچ تیکتی ایجاد نکرده‌اید
                    @endif
                </p>
                <a href="{{ route('dashboard.tickets.create') }}" class="btn btn-primary" style="font-size: 14px; background: #D39D1A; border-color: #D39D1A; color: white; border-radius: 12px;">
                    <i class="bi bi-plus-circle me-1"></i>
                    ایجاد تیکت جدید
                </a>
            </div>
        @endif
    </div>
@endsection

