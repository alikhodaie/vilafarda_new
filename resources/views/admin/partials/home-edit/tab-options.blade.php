@include('admin.partials.home-edit.help', ['text' => '<strong>امکانات:</strong> گزینه‌هایی که اقامتگاه دارد (استخر، پارکینگ و ...). در صفحهٔ نمایش با آیکون نشان داده می‌شوند.'])

<div class="row">
    @foreach(\App\Models\Option::getFromCache() as $option)
        <div class="col-6 col-md-4 col-lg-3 mb-3">
            <label for="option-{{ $option->id }}" class="admin-option-chip d-block border rounded-3 p-2 text-center cursor-pointer mb-0 h-100 {{ $home->options->contains($option->id) ? 'border-warning bg-light' : '' }}">
                <input type="checkbox" {{ $home->options->contains($option->id) ? 'checked' : '' }}
                       id="option-{{ $option->id }}" class="form-check-input d-none admin-option-check" name="options[]"
                       value="{{ $option->id }}">
                <img src="{{ $option->icon_path }}" alt="" width="48" height="48" class="mb-1 d-block mx-auto">
                <span class="small d-block">{{ $option->title }}</span>
            </label>
        </div>
    @endforeach
</div>
