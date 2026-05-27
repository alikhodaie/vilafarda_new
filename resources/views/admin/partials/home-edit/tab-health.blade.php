@include('admin.partials.home-edit.help', ['text' => '<strong>بهداشت:</strong> موارد بهداشتی موجود در اقامتگاه. موارد اضافه را در کادر پایین بنویسید.'])

<div class="row mb-3">
    @foreach(\App\Models\Health::getFromCache() as $health)
        <div class="col-12 col-md-6 mb-2">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" {{ $home->healths->contains($health->id) ? 'checked' : '' }}
                       id="health-{{ $health->id }}" name="healths[]" value="{{ $health->id }}">
                <label for="health-{{ $health->id }}" class="form-check-label">{{ $health->title }}</label>
            </div>
        </div>
    @endforeach
</div>
<div class="mb-0">
    <label for="more_health" class="form-label">موارد بیشتر</label>
    <textarea id="more_health" class="form-control" rows="2"
              name="more_health">{{ old('more_health', $home->more_health) }}</textarea>
</div>
