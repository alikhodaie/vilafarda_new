@extends('layouts.main.main', ['title' => setting('faq:title')])

@section('content')
    <!-- ============================ Page Title Start================================== -->
    <section class="image-cover faq-sec text-center" style="background:url({{ settingFilePath('faq:banner') }}) no-repeat;" data-overlay="6">
        <div class="container">
            <div class="row">

                <div class="col-lg-12 col-md-12">
                    <h1 class="text-light">{{ setting('faq:title') }}</h1>
                    <div class="faq-search">
                        <form>
                            <input name="search" class="form-control" placeholder="@lang('title.search') ..." value="{{ request('search') }}">
                            <button type="submit" class="theme-cl"> <i class="ti-search"></i> </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- ============================ Page Title End ================================== -->

    <!-- ================= Our Mission ================= -->
    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-md-12 col-sm-12">

                    @foreach($categories as $category)
                        @if($category->faq->isNotEmpty())
                            <!-- Single -->
                            <div class="faq_wrap">
                                <div class="faq_wrap_title">
                                    <h4>{{ $category->title }}</h4>
                                </div>
                                <div class="faq_wrap_body mb-5">
                                    <div class="accordion" id="{{ $category->name }}-{{ $category->id }}">
                                        @foreach($category->faq as $faq)
                                            <div class="card">
                                                <div class="card-header" id="heading-{{ $faq->id }}">
                                                    <h2 class="mb-0">
                                                        <button style="white-space: normal" class="btn btn-link @if(!$loop->first) collapsed @endif pl-5 pl-md-5" type="button" data-toggle="collapse" data-target="#collapse-{{ $faq->id }}" aria-controls="collapse-{{ $faq->id }}">
                                                            {{ $faq->question }}
                                                        </button>
                                                    </h2>
                                                </div>

                                                <div id="collapse-{{ $faq->id }}" class="collapse @if($loop->first) show @endif" aria-labelledby="heading-{{ $faq->id }}" data-parent="#{{ $category->name }}-{{ $category->id }}">
                                                    <div class="card-body">
                                                        <p class="ac-para">{{ $faq->answer }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                </div>
            </div>
        </div>
    </section>
    <!-- ================= Our Mission ================= -->
@endsection
