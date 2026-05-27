<form method="POST" action="{{ route('main.contact.store') }}" class="block-body">
    @csrf

    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="form-group">
                <label for="name">@lang('title.name')</label>
                <input id="name" name="name" value="{{ old('name') }}" type="text" class="form-control simple">
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="form-group">
                <label for="mobile">@lang('title.mobile')</label>
                <input id="mobile" name="mobile" value="{{ old('mobile') }}" type="text" class="form-control simple">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="subject">@lang('title.subject')</label>
        <input id="subject" name="subject" value="{{ old('subject') }}" type="text" class="form-control simple">
    </div>

    <div class="form-group">
        <label for="message">@lang('title.message')</label>
        <textarea id="message" name="message" class="form-control simple">{!! old('message') !!}</textarea>
    </div>

    <div class="form-group">
        <button class="btn btn-theme" type="submit">@lang('title.send_request')</button>
    </div>
</form>
