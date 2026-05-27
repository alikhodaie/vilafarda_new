@php use App\Models\Setting; @endphp
@extends('layouts.admin.admin', ['title' => __('title.setting'), 'active' => 'setting'])

@section('content')
    <div class="card" id="accordionSetting">
        <div class="card-header">
            @if(auth()->user()->can('general', \App\Models\Setting::class))
                <button id="generalBtn" class="btn btn-falcon-default mx-2 mb-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#general" aria-controls="general">@lang('title.general')</button>
            @endif
            @if(auth()->user()->can('payment', \App\Models\Setting::class))
                <button id="paymentBtn" class="btn btn-falcon-default mx-2 mb-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#payment" aria-controls="payment">@lang('title.payment')</button>
            @endif
            @if(auth()->user()->can('rejectPolicy', \App\Models\Setting::class))
                <button id="rejectPolicyBtn" class="btn btn-falcon-default mx-2 mb-2" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#rejectPolicy" aria-controls="rejectPolicy">@lang('title.reject_policy')</button>
            @endif
            @if(auth()->user()->can('commission', \App\Models\Setting::class))
                <button id="commissionBtn" class="btn btn-falcon-default mx-2 mb-2" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#commission" aria-controls="commission">@lang('title.commission')</button>
            @endif
            @if(auth()->user()->can('indexPage', \App\Models\Setting::class))
                <button id="indexBtn" class="btn btn-falcon-default mx-2 mb-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#index" aria-controls="index">@lang('title.index_page')</button>
            @endif
            @if(auth()->user()->can('submitHome', Setting::class))
                <button id="submitHomeBtn" class="btn btn-falcon-default mx-2 mb-2" type="button"
                        data-bs-toggle="collapse" data-bs-target="#submitHome"
                        aria-controls="index">@lang('title.submit_home')</button>
            @endif
            @if(auth()->user()->can('aboutUs', \App\Models\Setting::class))
                <button id="aboutUsBtn" class="btn btn-falcon-default mx-2 mb-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#aboutUs" aria-controls="aboutUs">@lang('title.about-us')</button>
            @endif
            @if(auth()->user()->can('contactUs', \App\Models\Setting::class))
                <button id="contactUsBtn" class="btn btn-falcon-default mx-2 mb-2" type="button"
                        data-bs-toggle="collapse" data-bs-target="#contactUs"
                        aria-controls="contactUs">@lang('title.contact-us')</button>
            @endif
            @if(auth()->user()->can('privacy', \App\Models\Setting::class))
                <button id="privacyBtn" class="btn btn-falcon-default mx-2 mb-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#privacy" aria-controls="contactUs">@lang('title.privacy')</button>
            @endif
            @if(auth()->user()->can('faq', Setting::class))
                <button id="faqBtn" class="btn btn-falcon-default mx-2 mb-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#faq" aria-controls="faq">@lang('title.faq')</button>
            @endif
            @if(auth()->user()->can('footer', \App\Models\Setting::class))
                <button id="footerBtn" class="btn btn-falcon-default mx-2 mb-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#footer" aria-controls="footer">@lang('title.footer')</button>
            @endif
            @if(auth()->user()->can('seo', Setting::class))
                <button id="seoBtn" class="btn btn-falcon-default mx-2 mb-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#seo" aria-controls="seo">@lang('title.seo')</button>
            @endif
            <hr>
        </div>

        <div class="card-body pt-0">
            @if(auth()->user()->can('general', \App\Models\Setting::class))
                <div class="accordion-collapse collapse" id="general" aria-labelledby="general"
                     data-bs-parent="#accordionSetting">
                    <div class="accordion-body">
                        @include('admin.setting.pages.general')
                    </div>
                </div>
            @endif
            @if(auth()->user()->can('payment', \App\Models\Setting::class))
                <div class="accordion-collapse collapse" id="payment" aria-labelledby="payment"
                     data-bs-parent="#accordionSetting">
                    <div class="accordion-body">
                        @include('admin.setting.pages.payment')
                    </div>
                </div>
            @endif
            @if(auth()->user()->can('rejectPolicy', \App\Models\Setting::class))
                <div class="accordion-collapse collapse" id="rejectPolicy" aria-labelledby="rejectPolicy"
                     data-bs-parent="#accordionSetting">
                    <div class="accordion-body">
                        @include('admin.setting.pages.reject_policy')
                    </div>
                </div>
            @endif
            @if(auth()->user()->can('commission', \App\Models\Setting::class))
                <div class="accordion-collapse collapse" id="commission" aria-labelledby="commission"
                     data-bs-parent="#accordionSetting">
                    <div class="accordion-body">
                        @include('admin.setting.pages.commission')
                    </div>
                </div>
            @endif
            @if(auth()->user()->can('indexPage', \App\Models\Setting::class))
                <div class="accordion-collapse collapse" id="index" aria-labelledby="index"
                     data-bs-parent="#accordionSetting">
                    <div class="accordion-body">
                        @include('admin.setting.pages.index-page')
                    </div>
                </div>
            @endif
            @if(auth()->user()->can('submitHome', \App\Models\Setting::class))
                <div class="accordion-collapse collapse" id="submitHome" aria-labelledby="submitHome"
                     data-bs-parent="#accordionSetting">
                    <div class="accordion-body">
                        @include('admin.setting.pages.submit-home')
                    </div>
                </div>
            @endif
            @if(auth()->user()->can('contactUs', \App\Models\Setting::class))
                <div class="accordion-collapse collapse" id="contactUs" aria-labelledby="contactUs"
                     data-bs-parent="#accordionSetting">
                    <div class="accordion-body">
                        @include('admin.setting.pages.contact-us')
                    </div>
                </div>
            @endif
            @if(auth()->user()->can('privacy', \App\Models\Setting::class))
                <div class="accordion-collapse collapse" id="privacy" aria-labelledby="privacy"
                     data-bs-parent="#accordionSetting">
                    <div class="accordion-body">
                        @include('admin.setting.pages.privacy')
                    </div>
                </div>
            @endif
            @if(auth()->user()->can('aboutUs', \App\Models\Setting::class))
                <div class="accordion-collapse collapse" id="aboutUs" aria-labelledby="aboutUs"
                     data-bs-parent="#accordionSetting">
                    <div class="accordion-body">
                        @include('admin.setting.pages.about-us')
                    </div>
                </div>
            @endif
            @if(auth()->user()->can('faq', \App\Models\Setting::class))
                <div class="accordion-collapse collapse" id="faq" aria-labelledby="faq"
                     data-bs-parent="#accordionSetting">
                    <div class="accordion-body">
                        @include('admin.setting.pages.faq')
                    </div>
                </div>
            @endif
            @if(auth()->user()->can('footer', \App\Models\Setting::class))
                <div class="accordion-collapse collapse" id="footer" aria-labelledby="footer"
                     data-bs-parent="#accordionSetting">
                    <div class="accordion-body">
                        @include('admin.setting.pages.footer')
                    </div>
                </div>
            @endif
            @if(auth()->user()->can('seo', Setting::class))
                <div class="accordion-collapse collapse" id="seo" aria-labelledby="seo"
                     data-bs-parent="#accordionSetting">
                    <div class="accordion-body">
                        @include('admin.setting.pages.seo')
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('bottom-assets')
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const active = urlParams.get('active');

        if (active) {
            const activeBtn = document.getElementById(active + 'Btn');
            if (activeBtn) {
                activeBtn.click();
            }
        }
    </script>
@endsection
