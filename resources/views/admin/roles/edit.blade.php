@extends('layouts.admin.admin', ['title' => __('title.edit role'), 'active' => 'roles'])

@section('content')
    <x-admin.card title="{{ __('title.edit role') }}">
        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" class="p-3 row">
            @csrf
            @method('PUT')

            <div class="col-12 mb-5">
                <label class="form-label" for="title">@lang('title.title') <span>*</span></label>
                <input class="form-control" name="title" id="title" type="text" value="{{ old('title', $role->fa_name) }}"/>
            </div>

            @foreach($group as $name => $permissions)
                <div class="col-12 mb-5">
                    <h6>{{ $name }}</h6>
                    <hr>
                    <div class="d-inline-block">
                        @foreach($permissions as $permission)
                            <div class="mx-2 d-inline">
                                <label class="form-check-label" for="permission-{{ $permission->id }}">{{ $permission->fa_name }}</label>
                                <input @if((old('permissions') && (in_array($permission->id, old('permissions')))) || $role->permissions->contains($permission->id)) checked @endif type="checkbox" name="permissions[]" id="permission-{{ $permission->id }}" value="{{ $permission->id }}" class="form-check-input">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="col-12 mt-5 d-flex justify-content-center">
                <button class="btn btn-falcon-success">@lang('title.edit')</button>
                <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
            </div>
        </form>
    </x-admin.card>
@endsection
