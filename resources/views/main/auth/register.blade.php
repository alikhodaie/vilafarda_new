@extends('layouts.main.main', ['title' => __('title.register')])

@section('content')
    <div class="container">
        <div class="d-flex justify-content-center my-5">
            <div class="my-5">
                <x-auth.register>
                    <x-slot name="show_login_button">
                        true
                    </x-slot>
                </x-auth.register>
            </div>
        </div>
    </div>
@endsection
