@extends('layouts.admin.admin', ['title' => __('title.homes'), 'active' => 'homes'])

@section('content')
    <x-admin.search-card route="{{ route('admin.homes.index') }}">
        <div class="col-12 col-md-4 mt-2">
            <label for="id">@lang('title.id')</label>
            <input type="text" class="form-control" name="id" id="id" value="{{ request('id') }}">
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="name">@lang('title.name')</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ request('name') }}">
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="user">@lang('title.user')</label>
            <user-input
                @if(request()->filled('user'))
                old="{{ request('user') }}"
                @endif
                route="{{ route('admin.ajax.users') }}"
                placeholder="@lang('text.select_user')"
                select_label="@lang('title.select')"
                selected_label="@lang('title.selected')"
                deselect_label="@lang('title.remove')"
                no_result_text="@lang('text.empty_result')"
                no_options_text="@lang('text.empty_list')"
            ></user-input>
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="status">@lang('title.status')</label>
            <select name="status" id="status" class="form-control">
                <option value="">@lang('title.select')</option>
                @foreach(\App\Models\Home::STATUSES as $status)
                    <option value="{{ $status['value'] }}" @if($status['value'] == request('status')) selected @endif>{{ $status['fa_text'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="province">@lang('title.province')</label>
            <province-input
                route="{{ route('dashboard.provinces') }}"
                placeholder="@lang('text.insert_province')"
                select_label="@lang('title.select')"
                selected_label="@lang('title.selected')"
                deselect_label="@lang('title.remove')"
                no_result_text="@lang('text.empty_result')"
                no_options_text="@lang('text.empty_list')"
                old="{{ request('province') }}"
            ></province-input>
        </div>
        <div class="col-12 col-md-4 mt-2">
            <label for="city">@lang('title.city')</label>
            <city-input
                placeholder="@lang('text.insert_city')"
                select_label="@lang('title.select')"
                selected_label="@lang('title.selected')"
                deselect_label="@lang('title.remove')"
                no_result_text="@lang('text.empty_result')"
                no_options_text="@lang('text.empty_list')"
                old="{{ request('city') }}"
            ></city-input>
        </div>
    </x-admin.search-card>

    <x-admin.card
        title="{{ __('title.homes') }}"
        canSeeButton="{{ auth()->user()->can('create', \App\Models\Home::class) }}"
        buttonLink="{{ route('admin.homes.create') }}">

        <x-slot name="buttonText">
            <i class="fa fa-plus"></i>
        </x-slot>

        <div class="table-responsive scrollbar">
            @if($homes->isEmpty())
                <x-admin.empty-message></x-admin.empty-message>
            @else
                <table class="table table-hover table-striped overflow-hidden">
                    <thead>
                        <tr>
                            <th scope="col">@lang('title.id')</th>
                            <th scope="col">@lang('title.user')</th>
                            <th scope="col">@lang('title.name')</th>
                            <th scope="col">@lang('title.status')</th>
                            <th scope="col">وضعیت میزبان</th>
                            <th class="text-end" scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($homes as $home)
                        <tr class="align-middle">
                            <td class="text-nowrap">{{ $home->id }}</td>
                            <td class="text-nowrap">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <span>{{ $home->user->full_name }}</span>
                                    <x-admin.user-contact-button :user="$home->user" />
                                </div>
                            </td>
                            <td class="text-nowrap">
                                <div class="d-flex align-items-center">
                                    @if($home->cover)
                                        <img width="75" src="{{ $home->cover_path }}" alt="{{ $home->title }}">
                                    @endif
                                    <div class="ms-2">{{ $home->name }}</div>
                                </div>
                            </td>
                            <td class="text-nowrap">
                                <span class="badge rounded-pill d-block p-2 badge-soft-{{ $home->status('color') }}">{{ $home->status() }}</span>
                            </td>
                            <td class="text-nowrap" style="min-width: 160px;">
                                @if($home->isHostActive())
                                    <span class="badge rounded-pill d-block p-2 badge-soft-success">فعال</span>
                                @else
                                    <span class="badge rounded-pill d-block p-2 badge-soft-secondary">غیرفعال</span>
                                    @if($home->hostDeactivationReasonLabel())
                                        <small class="d-block mt-1 text-muted" style="font-size: 11px; line-height: 1.5;">
                                            {{ $home->hostDeactivationReasonLabel() }}
                                        </small>
                                    @endif
                                @endif
                            </td>
                            <td class="text-end">
                                @can('showDate', $home)
                                    <a href="{{ route('admin.homes.date.show', $home->id) }}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.edit') @lang('title.calendar')">
                                        <span class="text-500 fas fa-calendar"></span>
                                    </a>
                                @endcan
                                @can('update', $home)
                                    <a href="{{ route('admin.homes.edit', $home->id) }}" class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('title.edit')">
                                        <span class="text-500 fas fa-edit"></span>
                                    </a>
                                @endcan
                                @can('delete', $home)
                                    <delete-modal
                                        route="{{ route('admin.homes.destroy', $home->id) }}"
                                        csrf="{{ csrf_token() }}"
                                        title="@lang('title.delete home')"
                                        text="@lang('text.delete home')"
                                        button_hover_text="@lang('title.delete')"
                                        button_cancel_text="@lang('title.cancel')"
                                        button_submit_text="@lang('title.delete')"
                                    ></delete-modal>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $homes->links() }}
                </div>
            @endif
        </div>

    </x-admin.card>

    <div class="modal fade" id="adminUserContactModal" tabindex="-1" aria-labelledby="adminUserContactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="adminUserContactModalLabel">اطلاعات تماس</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body pt-2">
                    <p class="fw-semibold mb-3" id="adminUserContactName">—</p>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2 d-flex align-items-start gap-2">
                            <span class="fas fa-mobile-alt text-500 mt-1"></span>
                            <span>
                                <span class="text-muted d-block">موبایل</span>
                                <a href="#" id="adminUserContactMobile" class="fw-semibold text-decoration-none">—</a>
                            </span>
                        </li>
                        <li class="mb-2 d-flex align-items-start gap-2">
                            <span class="fas fa-envelope text-500 mt-1"></span>
                            <span>
                                <span class="text-muted d-block">ایمیل</span>
                                <a href="#" id="adminUserContactEmail" class="fw-semibold text-decoration-none text-break">—</a>
                            </span>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <span class="fas fa-hashtag text-500 mt-1"></span>
                            <span>
                                <span class="text-muted d-block">شناسه کاربر</span>
                                <span id="adminUserContactId" class="fw-semibold">—</span>
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <a href="#" id="adminUserContactEditLink" class="btn btn-primary btn-sm d-none">مشاهده پروفایل</a>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('bottom-assets')
    <script>
        document.getElementById('adminUserContactModal').addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;
            if (!trigger) return;

            const name = trigger.dataset.userName || '—';
            const mobile = trigger.dataset.userMobile || '';
            const email = trigger.dataset.userEmail || '';
            const userId = trigger.dataset.userId || '—';
            const editUrl = trigger.dataset.userEditUrl || '';

            document.getElementById('adminUserContactName').textContent = name;
            document.getElementById('adminUserContactId').textContent = userId;

            const mobileEl = document.getElementById('adminUserContactMobile');
            if (mobile) {
                mobileEl.textContent = mobile;
                mobileEl.href = 'tel:' + mobile;
                mobileEl.classList.remove('text-muted');
            } else {
                mobileEl.textContent = 'ثبت نشده';
                mobileEl.removeAttribute('href');
                mobileEl.classList.add('text-muted');
            }

            const emailEl = document.getElementById('adminUserContactEmail');
            if (email) {
                emailEl.textContent = email;
                emailEl.href = 'mailto:' + email;
                emailEl.classList.remove('text-muted');
            } else {
                emailEl.textContent = 'ثبت نشده';
                emailEl.removeAttribute('href');
                emailEl.classList.add('text-muted');
            }

            const editLink = document.getElementById('adminUserContactEditLink');
            if (editUrl) {
                editLink.href = editUrl;
                editLink.classList.remove('d-none');
            } else {
                editLink.classList.add('d-none');
            }
        });
    </script>
@endsection
