@if(! $home->isBookingEnabled())
    <div class="home-deactivated-notice" role="status">
        <i class="bi bi-calendar-x" aria-hidden="true"></i>
        <div>
            <strong>این اقامتگاه فعلاً غیرفعال است</strong>
            <p>امکان رزرو وجود ندارد و همه روزهای تقویم بسته هستند.</p>
        </div>
    </div>
    <style>
        .home-deactivated-notice {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin: 12px 0 16px;
            padding: 14px 16px;
            border-radius: 12px;
            background: #fff6e5;
            border: 1px solid #f0d9a8;
            color: #5c430d;
        }
        .home-deactivated-notice i {
            font-size: 22px;
            line-height: 1.2;
            color: #c48a12;
        }
        .home-deactivated-notice strong,
        .home-deactivated-notice p {
            display: block;
            margin: 0;
        }
        .home-deactivated-notice p {
            margin-top: 4px;
            font-size: 13px;
            line-height: 1.7;
        }
    </style>
@endif
