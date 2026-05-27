<div class="container-fluid bg-white px-4 rounded-3">

    <!-- میزبانی اقامتگاه -->
    <h6 class="text-muted fw-bold mb-2">میزبانی اقامتگاه</h6>

    <a href="{{ route('dashboard.homes.create') }}" class="profile-link">
        <div class="d-flex align-items-center gap-3 justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-square">
                    <i class="fa fa-plus-square fs-3"></i>
                </div>
                <div>
                    <div class="fw-semibold">ثبت اقامتگاه</div>
                    <small class="text-muted">اقامتگاه جدید ثبت کنید</small>
                </div>
            </div>
            <i class="bi bi-chevron-left fs-5 text-muted"></i>
        </div>
        <hr>
    </a>

    <a href="{{ route('dashboard.homes.index') }}" class="profile-link">
        <div class="d-flex align-items-center gap-3 justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-square">
                    <i class="fa fa-home fs-3"></i>
                </div>
                <div>
                    <div class="fw-semibold">اقامتگاه‌های من</div>
                    <small class="text-muted">مدیریت اقامتگاه‌های ثبت‌شده</small>
                </div>
            </div>
            <i class="bi bi-chevron-left fs-5 text-muted"></i>
        </div>
        <hr>
    </a>

    <a href="{{ route('dashboard.orders.index') }}" class="profile-link">
        <div class="d-flex align-items-center gap-3 justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-square">
                    <i class="bi bi-bookmark-check-fill fs-3"></i>
                </div>
                <div>
                    <div class="fw-semibold">درخواست‌های رزرو</div>
                    <small class="text-muted">درخواست‌های رزرو اقامتگاه شما</small>
                </div>
            </div>
            <i class="bi bi-chevron-left fs-5 text-muted"></i>
        </div>
        <hr>
    </a>

    <a href="{{ route('dashboard.comments.index') }}" class="profile-link">
        <div class="d-flex align-items-center gap-3 justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-square">
                    <i class="fa fa-comments fs-3"></i>
                </div>
                <div>
                    <div class="fw-semibold">پاسخ به نظرات</div>
                    <small class="text-muted">پاسخ به نظرات مهمانان</small>
                </div>
            </div>
            <i class="bi bi-chevron-left fs-5 text-muted"></i>
        </div>
        <hr>
    </a>

    <a href="{{ route('dashboard.host-transactions.index') }}" class="profile-link">
        <div class="d-flex align-items-center gap-3 justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-square">
                    <i class="fa fa-dollar-sign fs-3"></i>
                </div>
                <div>
                    <div class="fw-semibold">@lang('title.host_transactions')</div>
                    <small class="text-muted">تسویه و پرداخت‌های مربوط به اقامتگاه شما</small>
                </div>
            </div>
            <i class="bi bi-chevron-left fs-5 text-muted"></i>
        </div>
        <hr>
    </a>

    <!-- میهمان -->
    <h6 class="text-muted fw-bold mt-4">میهمان</h6>

    <a href="{{ route('dashboard.favorites.index') }}" class="profile-link">
        <div class="d-flex align-items-center gap-3 justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-square">
                    <i class="bi bi-heart-fill fs-3"></i>
                </div>
                <div>
                    <div class="fw-semibold">علاقه‌مندی‌ها</div>
                    <small class="text-muted">آیتم‌های مورد علاقه شما</small>
                </div>
            </div>
            <i class="bi bi-chevron-left fs-5 text-muted"></i>
        </div>
        <hr>
    </a>

    <a href="{{ route('dashboard.rents.index') }}" class="profile-link">
        <div class="d-flex align-items-center gap-3 justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-square">
                    <i class="bi bi-suitcase2-fill fs-3"></i>
                </div>
                <div>
                    <div class="fw-semibold">سفرهای من</div>
                    <small class="text-muted">ویلاهایی که اجاره کرده‌اید</small>
                </div>
            </div>
            <i class="bi bi-chevron-left fs-5 text-muted"></i>
        </div>
        <hr>
    </a>

    <a href="{{ route('dashboard.guest-transactions.index') }}" class="profile-link">
        <div class="d-flex align-items-center gap-3 justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-square">
                    <i class="fa fa-credit-card fs-3"></i>
                </div>
                <div>
                    <div class="fw-semibold">@lang('title.guest_transactions')</div>
                    <small class="text-muted">پرداخت‌های شما برای رزرو اقامتگاه</small>
                </div>
            </div>
            <i class="bi bi-chevron-left fs-5 text-muted"></i>
        </div>
        <hr>
    </a>

    <a href="{{ route('dashboard.rents.index', ['tab' => \App\Models\Order::TRIP_TAB_AWAITING_REVIEW]) }}" class="profile-link">
        <div class="d-flex align-items-center gap-3 justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-square">
                    <i class="bi bi-star-fill fs-3"></i>
                </div>
                <div>
                    <div class="fw-semibold">ثبت نظر</div>
                    <small class="text-muted">سفرهای در انتظار ثبت نظر</small>
                </div>
            </div>
            <i class="bi bi-chevron-left fs-5 text-muted"></i>
        </div>
        <hr>
    </a>

    <!-- حساب کاربری -->
    <h6 class="text-muted fw-bold mt-4">حساب کاربری</h6>

    @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.index') }}" class="profile-link">
            <div class="d-flex align-items-center gap-3 justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-square">
                        <i class="fa fa-user-ninja fs-3"></i>
                    </div>
                    <div>
                        <div class="fw-semibold">@lang('title.admin')</div>
                        <small class="text-muted">ورود به پنل ادمین</small>
                    </div>
                </div>
                <i class="bi bi-chevron-left fs-5 text-muted"></i>
            </div>
            <hr>
        </a>
    @endif

    <a href="{{ route('dashboard.profile.edit') }}" class="profile-link">
        <div class="d-flex align-items-center gap-3 justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-square">
                    <i class="fa fa-user fs-3"></i>
                </div>
                <div>
                    <div class="fw-semibold">اطلاعات حساب کاربری</div>
                    <small class="text-muted">مشاهده و ویرایش اطلاعات شخصی</small>
                </div>
            </div>
            <i class="bi bi-chevron-left fs-5 text-muted"></i>
        </div>
        <hr>
    </a>

    <a href="{{ route('dashboard.tickets.index') }}" class="profile-link">
        <div class="d-flex align-items-center gap-3 justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-square">
                    <i class="bi bi-ticket-fill fs-3"></i>
                </div>
                <div>
                    <div class="fw-semibold">تیکت‌ها</div>
                    <small class="text-muted">پیگیری درخواست‌های پشتیبانی</small>
                </div>
            </div>
            <i class="bi bi-chevron-left fs-5 text-muted"></i>
        </div>
        <hr>
    </a>

    <a href="{{ route('main.contact-us') }}" class="profile-link">
        <div class="d-flex align-items-center gap-3 justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-square">
                    <i class="bi bi-telephone-fill fs-3"></i>
                </div>
                <div>
                    <div class="fw-semibold">تماس با ویلا فردا</div>
                    <small class="text-muted">راه‌های ارتباط با پشتیبانی</small>
                </div>
            </div>
            <i class="bi bi-chevron-left fs-5 text-muted"></i>
        </div>
        <hr>
    </a>

    <a href="javascript:" onclick="document.getElementById('logout').click()" class="profile-link">
        <div class="d-flex align-items-center gap-3 justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-square">
                    <i class="bi bi-box-arrow-right text-danger fs-3"></i>
                </div>
                <div>
                    <div class="fw-semibold text-danger">خروج از حساب کاربری</div>
                </div>
            </div>
            <i class="bi bi-chevron-left fs-5 text-danger"></i>
        </div>
        <hr>
    </a>

</div>

<style>
    .profile-link {
        display: block;
        text-decoration: none;
        color: inherit;
    }
    .profile-link:hover {
        background-color: #f9fafb;
        border-radius: 8px;
    }

    hr {
        margin: 2px !important;
        color: #eee;
    }

</style>
