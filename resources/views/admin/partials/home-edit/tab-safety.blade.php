@php use App\Models\Safety; @endphp

@include('admin.partials.home-edit.help', ['text' => '<strong>ایمنی:</strong> تجهیزات و نکات ایمنی. برای برخی موارد می‌توانید توضیح تکمیلی وارد کنید.'])

<div class="row mb-3">
    @foreach(Safety::getFromCache() as $index => $safety)
        <div class="col-12 col-md-6 mb-3">
            <div class="form-check mb-2">
                <input type="checkbox" {{ $home->safeties->contains($safety->id) ? 'checked' : '' }}
                       id="safety-{{ $safety->id }}" class="form-check-input admin-safety-toggle"
                       name="safeties[{{ $index }}][id]" value="{{ $safety->id }}"
                       data-target="admin-safety-desc-{{ $safety->id }}">
                <label for="safety-{{ $safety->id }}" class="form-check-label">{{ $safety->title }}</label>
            </div>
            <input type="text" placeholder="{{ $safety->placeholder }}" class="form-control form-control-sm
                   {{ $home->safeties->contains($safety->id) ? '' : 'd-none' }}"
                   id="admin-safety-desc-{{ $safety->id }}"
                   name="safeties[{{ $index }}][description]"
                   value="{{ $home->safeties()->where('safety_id', $safety->id)->first()->pivot->description ?? '' }}">
        </div>
    @endforeach
</div>
<div class="mb-0">
    <label for="more_safety" class="form-label">موارد بیشتر</label>
    <textarea id="more_safety" class="form-control" rows="2"
              name="more_safety">{{ old('more_safety', $home->more_safety) }}</textarea>
</div>
