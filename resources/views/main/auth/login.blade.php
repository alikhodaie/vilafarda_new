@extends('layouts.main.main', ['title' => __('title.login')])

@section('content')
    <div class="container">
        <div class="d-flex justify-content-center my-5">
            <div class="my-5">
                <x-auth.login>
                    <x-slot name="show_register_button">
                        true
                    </x-slot>
                </x-auth.login>
            </div>
        </div>
    </div>
@endsection
