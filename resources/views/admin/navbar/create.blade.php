@extends('layouts.admin.admin', ['title' => __('title.create navbar'), 'active' => 'navbar'])

@section('content')
    <x-admin.card title="{{ __('title.create navbar') }}">
        <form action="{{ route('admin.navbar.store') }}" method="POST" class="p-3 row">
            @csrf

            <div class="col-12 mb-5">
                <label class="form-label" for="title">@lang('title.title') <span>*</span></label>
                <input class="form-control" name="title" id="title" type="text" value="{{ old('title') }}"/>
            </div>

            <div class="col-12 mb-5">
                <label class="form-label" for="link">@lang('title.link') <span>*</span></label>
                <input class="form-control" name="link" id="link" type="url" value="{{ old('link') }}"/>
            </div>

            <div class="col-12 mb-5">
                <label class="form-label" for="link">@lang('title.sort') <span>*</span></label>
                <input class="form-control" name="sort" min="0" id="sort" type="number" value="{{ old('sort', 0) }}"/>
            </div>

            <div class="col-12 mb-5">
                <label class="form-label" for="parent">@lang('title.parent')</label>
                <select name="parent" id="parent" class="form-control">
                    @php($items = \App\Models\Navbar::all())

                    @if($items->isEmpty())
                        <option value="">@lang('title.nothing found')</option>
                    @else
                        <option value="">@lang('title.select')</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" @if(old('parent') == $item->id) selected @endif>{{ $item->title }}({{ $item->id }})</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-12 mt-5 d-flex justify-content-center">
                <button class="btn btn-falcon-success">@lang('title.submit')</button>
                <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
                <a href="{{ route('admin.navbar.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
            </div>
        </form>
    </x-admin.card>
@endsection
