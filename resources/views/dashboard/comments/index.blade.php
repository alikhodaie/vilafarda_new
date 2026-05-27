@extends('layouts.dashboard.dashboard', ['title' => __('title.comments'), 'active' => 'comments', 'breadcrumbs' => [
    ['url' => null, 'title' => __('title.comments')]
]])

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            @if($comments->isNotEmpty())
                <div class="dashboard_property">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">@lang('title.guest')</th>
                                <th scope="col">@lang('title.comment')</th>
                                <th scope="col">@lang('title.home')</th>
                                <th scope="col" class="m2_hide">@lang('title.sent_at')</th>
                                <th scope="col">@lang('title.actions')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($comments as $comment)
                                <tr>
                                    <td>
                                        <div class="dash_prt_wrap">
                                            @if($comment->user)
                                                <div class="dash_prt_thumb">
                                                    <img src="{{ $comment->user->avatar_path }}" class="img-fluid rounded-circle" alt="{{ $comment->full_name }}" />
                                                </div>`
                                            @endif
                                            <div class="dash_prt_caption">
                                                <h5>{{ $comment->full_name }}</h5>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p>{{ $comment->comment }}</p>
                                    </td>
                                    <td>
                                        <a href="{{ route('dashboard.homes.index', ['name' => $comment->commentable->name]) }}">{{ $comment->commentable->name }}</a>
                                    </td>
                                    <td class="m2_hide">
                                        <div class="_leads_posted"><h5>{{ $comment->persianCreatedAt('d F - H:i') }}</h5></div>
                                        <div class="_leads_view_title"><span>{{ $comment->created_at->diffForHumans() }}</span></div>
                                    </td>
                                    <td>
                                        @if($comment->children_count !== 0)
                                            @if($comment->activeChildren->isNotEmpty())
                                                <div class="d-inline">
                                                    <!-- Button trigger modal -->
                                                    <a href="javascript:" data-toggle="modal" data-target="#reply-{{ $comment->id }}" class="btn btn-info">@lang('title.your_reply')</a>

                                                    <div class="modal" tabindex="-1" id="reply-{{ $comment->id }}">
                                                        <div class="modal-dialog modal-lg mt-6" role="document">
                                                            <div class="modal-content border-0">
                                                                <div class="modal-body p-0">
                                                                    <div class="bg-light rounded-top-lg py-3 ps-4 pe-6">
                                                                        <h4 class="mb-1 mr-4" style="text-align: right">@lang('title.your_reply')</h4>
                                                                    </div>
                                                                    <div class="p-4">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <p>{{ $comment->activeChildren->first()->comment }}</p>
                                                                            </div>
                                                                            <div class="col-12 mt-3">
                                                                                <div class="d-flex justify-content-around">
                                                                                    <button type="button" data-dismiss="modal" class="btn btn-danger mb-1">
                                                                                        @lang('title.close')
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="badge badge-danger">در انتظار تایید نظر شما</span>
                                            @endif
                                        @else
                                            <reply-comment
                                                route="{{ route('dashboard.comments.store') }}"
                                                csrf="{{ csrf_token() }}"
                                                title="@lang('title.reply_to', ['user' => $comment->full_name])"
                                                button_text="@lang('title.send_reply')"
                                                button_class="btn btn-success"
                                                text="{{ __('title.comment').': '.$comment->comment }}"
                                                comment_text="@lang('title.enter_comment')"
                                                button_cancel_text="@lang('title.cancel')"
                                                button_submit_text="@lang('title.send_reply')"
                                                comment_id="{{ $comment->id }}"
                                            ></reply-comment>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $comments->links() }}
                </div>
            @else
                <div class="alert alert-warning">
                    @lang('title.nothing found')
                </div>
            @endif
        </div>
    </div>
@endsection
