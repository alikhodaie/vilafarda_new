@extends('layouts.main.main_mobile', ['title' => __('title.my_homes')])

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    @php use App\Models\Home; @endphp
    @include('layouts.main.partials.navbar-mobile')
    
    <!-- Page Header -->
    <div class="container px-3 py-3">
        <div class="card-mobile">
            <h1 class="text-mobile-primary mb-2">@lang('title.my_homes')</h1>
            <p class="text-mobile-secondary mb-0">مدیریت اقامتگاه‌های شما</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="container px-3 py-3">
        <div class="row g-2">
            <div class="col-6">
                <button class="btn btn-mobile-primary w-100" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="bi bi-search me-2"></i>
                    جستجو
                </button>
            </div>
            <div class="col-6">
                <a href="{{ route('dashboard.homes.create') }}" class="btn btn-mobile-primary w-100">
                    <i class="bi bi-plus-circle me-2"></i>
                    اقامتگاه جدید
                </a>
            </div>
        </div>
    </div>

    <!-- Search Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel" style="font-size: 16px;">جستجو در اقامتگاه‌ها</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('dashboard.homes.index') }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label" style="font-size: 14px;">نام اقامتگاه</label>
                            <input name="name" value="{{ request('name') }}" type="text" class="form-control" 
                                   placeholder="نام اقامتگاه را وارد کنید..." style="font-size: 14px;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 14px; border-radius: 12px;">انصراف</button>
                        <button type="submit" class="btn btn-primary" style="background: #D39D1A; border-color: #D39D1A; color: white; font-size: 14px; border-radius: 12px;">
                            <i class="bi bi-search me-2"></i>
                            جستجو
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Homes List -->
    <div class="container px-3 pb-4">
        @if($homes->isNotEmpty())
            @foreach($homes as $home)
                <div class="bg-white rounded-3 p-3 mb-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div class="position-relative mb-3">
                        <img src="{{ $home->cover_path }}" class="img-fluid rounded" alt="{{ $home->name ?: 'اقامتگاه' }}"
                             style="width: 100%; height: 200px; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 m-2 d-flex flex-column gap-1 align-items-end">
                            @if($home->is_draft)
                                <span class="badge bg-warning text-dark" style="font-size: 12px;">اقامتگاه کامل ثبت نشده</span>
                            @else
                                <span class="badge bg-{{ $home->status('color') }}" style="font-size: 12px;">
                                    {{ $home->status() }}
                                </span>
                                @if(!$home->isHostActive())
                                    <span class="badge bg-secondary" style="font-size: 12px;">غیرفعال</span>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <h5 class="fw-bold mb-2" style="font-size: 16px; color: #333;">
                            {{ $home->name ?: 'پیش‌نویس ثبت اقامتگاه' }}
                        </h5>
                        <p class="text-muted mb-2" style="font-size: 14px; line-height: 1.4;">
                            {{ $home->description ? Str::limit(strip_tags($home->description), 100) : '—' }}
                        </p>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-geo-alt text-primary me-2" style="font-size: 14px;"></i>
                                    <small class="text-muted" style="font-size: 12px;">
                                        @if($home->province && $home->city)
                                            {{ $home->province->name }} - {{ $home->city->name }}
                                        @else
                                            موقعیت ثبت نشده
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-people text-primary me-2" style="font-size: 14px;"></i>
                                    <small class="text-muted" style="font-size: 12px;">
                                        {{ $home->main_guest ?: '—' }} مهمان
                                    </small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-house text-primary me-2" style="font-size: 14px;"></i>
                                    <small class="text-muted" style="font-size: 12px;">
                                        {{ $home->typeLabel() ?: '—' }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar text-primary me-2" style="font-size: 14px;"></i>
                                    <small class="text-muted" style="font-size: 12px;">
                                        {{ $home->updated_at->format('Y/m/d') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        @if($home->is_draft)
                            <a href="{{ route('dashboard.homes.create', ['continue' => 1, 'home' => $home->id]) }}" class="btn btn-primary btn-sm flex-grow-1" style="font-size: 12px; background: #D39D1A; border-color: #D39D1A; color: white; border-radius: 8px;">
                                <i class="bi bi-arrow-left-circle me-1"></i>
                                ادامه ثبت اقامتگاه
                            </a>
                        @else
                            @if($home->status === Home::ACCEPTED && $home->isHostActive())
                                <a href="{{ route('main.homes.show', $home) }}" class="btn btn-primary btn-sm flex-fill" style="font-size: 12px; background: #D39D1A; border-color: #D39D1A; color: white; border-radius: 8px;">
                                    <i class="bi bi-eye me-1"></i>
                                    مشاهده
                                </a>
                            @endif
                            <a href="{{ route('dashboard.homes.edit', $home) }}" class="btn btn-success btn-sm flex-fill" style="font-size: 12px; background: #28a745; border-color: #28a745; color: white; border-radius: 8px;">
                                <i class="bi bi-pencil me-1"></i>
                                ویرایش
                            </a>
                            <a href="{{ route('dashboard.homes.custom.date.show', $home) }}" class="btn btn-primary btn-sm flex-fill" style="font-size: 12px; background: #D39D1A; border-color: #D39D1A; color: white; border-radius: 8px;">
                                <i class="bi bi-calendar3 me-1"></i>
                                تقویم
                            </a>
                        @endif
                        @if($home->is_draft)
                            <button type="button" class="btn btn-danger btn-sm flex-fill" style="font-size: 12px; background: #dc3545; border-color: #dc3545; color: white; border-radius: 8px;"
                                    onclick="deleteHome({{ $home->id }})">
                                <i class="bi bi-trash me-1"></i>
                                حذف
                            </button>
                        @else
                            <div class="flex-fill" style="min-width: 120px;">
                                <x-dashboard.home.host-activation-button
                                    :home="$home"
                                    class="btn btn-sm w-100 {{ $home->isHostActive() ? 'btn-warning text-dark' : 'btn-success' }}"
                                    style="font-size: 12px; border-radius: 8px;" />
                                @if(!$home->isHostActive())
                                    <small class="text-muted d-block mt-1 text-center" style="font-size: 11px;">
                                        {{ $home->hostDeactivationReasonLabel() }}
                                    </small>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            @if($homes->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $homes->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-3 p-4 text-center" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <i class="bi bi-house fs-1 text-muted mb-3"></i>
                <h5 class="text-muted">هنوز اقامتگاهی ثبت نکرده‌اید</h5>
                <p class="text-muted mb-3" style="font-size: 14px;">
                    اولین اقامتگاه خود را ثبت کنید و کسب درآمد کنید
                </p>
                <a href="{{ route('dashboard.homes.create') }}" class="btn btn-primary" style="background: #D39D1A; border-color: #D39D1A; color: white; border-radius: 12px;">
                    <i class="bi bi-plus-circle me-2"></i>
                    ثبت اقامتگاه جدید
                </a>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <div style="width: 60px; height: 60px; background: #fee; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="bi bi-trash" style="font-size: 24px; color: #dc3545;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: #333;">حذف اقامتگاه</h5>
                    <p class="text-muted mb-4" style="font-size: 14px;">آیا مطمئن هستید که می‌خواهید این اقامتگاه را حذف کنید؟ این عمل قابل بازگشت نیست.</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 8px; padding: 8px 20px; font-size: 14px;">
                            انصراف
                        </button>
                        <button type="button" class="btn btn-danger" id="confirmDelete" style="border-radius: 8px; padding: 8px 20px; font-size: 14px;">
                            حذف کن
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    let currentHomeId = null;
    
    function deleteHome(homeId) {
        currentHomeId = homeId;
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
    
    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (currentHomeId) {
            // Create a form to submit the delete request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/dashboard/homes/${currentHomeId}`;
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfInput);
            
            // Add method override for DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            // Submit the form
            document.body.appendChild(form);
            form.submit();
        }
    });
    </script>
@endsection
