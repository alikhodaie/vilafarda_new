@extends('layouts.main.main_mobile', ['title' => __('title.create_ticket')])

@section('content')
    @include('layouts.main.partials.navbar-mobile')
    
    <!-- Page Header -->
    <div class="container px-3 py-3">
        <div class="bg-white rounded-3 p-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h1 class="fw-bold mb-2" style="font-size: 18px; color: #333;">@lang('title.create_ticket')</h1>
            <p class="mb-0" style="font-size: 14px; color: #666;">ایجاد تیکت پشتیبانی جدید</p>
        </div>
    </div>

    <!-- Create Form -->
    <div class="container px-3 py-4">
        <div class="bg-white rounded-3 p-3 mb-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <form action="{{ route('dashboard.tickets.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="title" class="form-label" style="font-size: 14px;">موضوع تیکت</label>
                    <input type="text" name="title" id="title" class="form-control" 
                           value="{{ old('title') }}" placeholder="موضوع تیکت خود را بنویسید..." 
                           style="font-size: 14px;" required>
                </div>
                
                <div class="mb-3">
                    <label for="message" class="form-label" style="font-size: 14px;">پیام</label>
                    <textarea name="message" id="message" class="form-control" rows="5" 
                              placeholder="پیام خود را به تفصیل بنویسید..." 
                              style="font-size: 14px;" required>{{ old('message') }}</textarea>
                </div>
                
                <button type="submit" class="btn btn-primary w-100" style="background: #D39D1A; border-color: #D39D1A; font-size: 14px;">
                    <i class="bi bi-send me-2"></i>
                    ارسال تیکت
                </button>
            </form>
        </div>

        <!-- Help -->
        <div class="bg-light rounded-3 p-3">
            <h6 class="fw-bold mb-2" style="font-size: 14px; color: #333;">راهنمای ایجاد تیکت:</h6>
            <ul class="mb-0" style="font-size: 12px; color: #666;">
                <li>موضوع تیکت را به صورت خلاصه و واضح بنویسید</li>
                <li>پیام خود را به تفصیل و با جزئیات کامل بنویسید</li>
                <li>در صورت امکان، تصاویر مربوطه را ضمیمه کنید</li>
            </ul>
        </div>
    </div>
@endsection




