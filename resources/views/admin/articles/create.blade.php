@extends('layouts.admin.admin', ['title' => __('title.create article'), 'active' => 'articles'])

@section('content')
    <x-admin.card title="{{ __('title.create article') }}">
        <form action="{{ route('admin.articles.store') }}" enctype="multipart/form-data" method="POST" class="p-3 row">
            @csrf

            <div class="col-12 mb-5">
                <div class="form-group row">
                    <div class="col-md-12 text-center">
                        <img id="cover" width="200" alt="cover"
                             src="{{ \App\Models\Article::DefaultImage() }}"/>
                    </div>
                    <div class="col-md-12 mt-5">
                        <div class="form-group">
                            <div class="custom-file">
                                <input onchange="readURL(this, 'cover');" type="file" class="form-control-file" name="image">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 mb-5">
                <label class="form-label" for="title">@lang('title.title') <span>*</span></label>
                <input class="form-control" name="title" id="title" type="text" value="{{ old('title') }}"/>
            </div>

            <div class="col-12 col-md-6 mb-5">
                <label class="form-label" for="slug">@lang('title.slug') <span>*</span></label>
                <input class="form-control" name="slug" id="slug" type="text" value="{{ old('slug') }}"/>
            </div>

            <div class="col-12 mb-5">
                <label class="form-label" for="category">@lang('title.category')</label>
                <select name="category" id="category" class="form-control">
                    @php($items = \App\Models\Category::query()->article()->get())

                    @if($items->isEmpty())
                        <option value="">@lang('title.nothing found')</option>
                    @else
                        <option value="">@lang('title.select')</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" @if(old('category') == $item->id) selected @endif>{{ $item->title }} ({{ $item->id }})</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-12 mb-5">
                <label class="form-label" for="summary">@lang('title.summary') <span>*</span></label>
                <textarea class="form-control" name="summary" id="summary" type="text">{!! old('summary') !!}</textarea>
            </div>

            <div class="col-12 mb-5">
                <label class="form-label" for="content">@lang('title.content') <span>*</span></label>
                <tinymce-editor
                    upload_file_route="{{ route('admin.tinymce_upload') }}"
                    upload_directory="{{ \App\Models\Article::getDescriptionPath() }}"
                    name="content"
                    lang="{{ config('app.tiny_locale') }}"
                    @if(old('content')) value="{{ old('content') }}" @endif
                ></tinymce-editor>
            </div>

            <div class="col-12 col-md-6 mb-5">
                <label class="form-label" for="meta">@lang('title.meta')</label>
                <meta-tag-input
                    placeholder="@lang('text.insert_meta')"
                    select_label="@lang('title.select')"
                    selected_label="@lang('title.selected')"
                    deselect_label="@lang('title.remove')"
                    tag_placeholder="@lang('title.add_meta')"
                    no_result_text="@lang('text.empty_result')"
                    no_options_text="@lang('text.empty_list')"
                    @if(old('metas')) :values="{{ collect(old('metas')) }}" @endif
                ></meta-tag-input>
            </div>

            <div class="col-12 col-md-6 mb-5">
                <label class="form-label" for="tags">@lang('title.tags')</label>
                <tag-input
                    placeholder="@lang('text.insert_tag')"
                    select_label="@lang('title.select')"
                    selected_label="@lang('title.selected')"
                    deselect_label="@lang('title.remove')"
                    tag_placeholder="@lang('title.add_tag')"
                    no_result_text="@lang('text.empty_result')"
                    no_options_text="@lang('text.empty_list')"
                    @if(old('tags')) :old="{{ \App\Models\Tag::query()->whereIn('id', old('tags'))->get() }}" @endif
                    route_index="{{ route('admin.tags.index') }}"
                    route_store="{{ route('admin.tags.store') }}"
                ></tag-input>
            </div>

            <div class="col-12 mt-5 d-flex justify-content-center">
                <button class="btn btn-falcon-success">@lang('title.submit')</button>
                <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
                <a href="{{ route('admin.articles.categories.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
            </div>
        </form>
    </x-admin.card>
@endsection

@section('bottom-assets')
    <script>
        function readURL(input, id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    // jQuery('#' + id).attr('src', e.target.result);
                    document.querySelector('#' + id).src = e.target.result
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
