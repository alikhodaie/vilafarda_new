<!-- Mobile Navbar -->
<div class="d-lg-none w-100 py-3 px-3 bg-white position-relative" style="z-index: 1050; border-bottom: 1px solid #e0e0e0; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
    <div class="d-flex justify-content-between align-items-center">
        <!-- Right: Site Name -->
        <div class="text-start" style="color:rgb(81 81 81);">
            <div class="fw-bold" style="letter-spacing:1px; font-size: 18px; line-height: 1.2;">ویلافردا</div>
            <div class="small" style="margin-top:2px; font-size: 11px; color: #666;">سایت اجاره ویلا و آپارتمان</div>
        </div>
        <!-- Left: Action Buttons -->
        <div class="d-flex align-items-center gap-3">

            <!-- Profile/Login Button -->
            @guest
                <a href="{{ route('main.login') }}" class="px-3 py-2 text-decoration-none" style="
                    color: #343434;
                    border: 1.5px solid #343434;
                    border-radius: 8px;
                    font-size: 13px;
                    background-color: transparent;
                    transition: all 0.3s ease;
                    display: inline-block;
                    font-weight: 500;
                " onmouseover="this.style.backgroundColor='#f5f5f5'" onmouseout="this.style.backgroundColor='transparent'">
                    ورود / ثبت‌نام
                </a>
            @else
                <a href="{{ route('main.login') }}" class="d-flex justify-content-center align-items-center rounded-circle" style="width:42px;height:42px;background:#fefefe;border:2px solid #f0f0f0; transition: all 0.3s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.1);" onmouseover="this.style.borderColor='#D39D1A'; this.style.boxShadow='0 2px 6px rgba(211,157,26,0.2)'" onmouseout="this.style.borderColor='#f0f0f0'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)'">
                    <img height="50px" width="50px" src="{{ auth()->user()->avatar_path }}" class="rounded-circle" style="object-fit: cover;">
                </a>
            @endguest

        </div>
    </div>
</div>
<!-- End Mobile Navbar --> 
@include('components.bottom-bar')
