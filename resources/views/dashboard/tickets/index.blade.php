@extends('layouts.dashboard.dashboard', ['title' => __('title.tickets'), 'active' => 'tickets', 'breadcrumbs' => [
    ['url' => null, 'title' => __('title.tickets')]
]])

@section('content')
    <h3 class="mb-3 mb-sm-0">
        @lang('title.tickets')
    </h3>

    <form class="d-flex align-items-center justify-content-start justify-content-sm-end">
        <div class="form-group ml-2">
            <input type="text" class="form-control" placeholder="@lang('title.search')" name="title" value="{{ request('title') }}">
        </div>

        <div class="form-group ml-2">
            <select class="form-control" id="sort_status" name="status">
                <option value="">@lang('title.status')</option>
                @foreach(\App\Models\Ticket::STATUS as $status)
                    <option value="{{ $status['value'] }}" @if($status['value'] === request('status')) selected @endif>{{ $status['fa_text'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <button class="btn btn-success rounded">@lang('title.filter')</button>
        </div>
    </form>

    @if($tickets->isNotEmpty())
        <div>
            @foreach($tickets as $ticket)
                <a href="{{ route('dashboard.tickets.show', $ticket->id) }}" class="card card-Orders-account mb-3 text-dark">
                    <div class="card-header">
                        <div class="row text-center">
                            <div class="col-12 col-md-2 mt-2 mt-md-0">#{{ $ticket->id }}</div>
                            <div class="col-12 col-md-6 mt-2 mt-md-0">{{ \Illuminate\Support\Str::limit($ticket->title, 15) }}</div>
                            <div class="col-12 col-md-2 mt-2 mt-md-0">
                                <span class="badge btn-{{ $ticket->status('color') }}">
                                    {{ $ticket->status() }}
                                </span>
                            </div>
                            <div class="col-12 col-md-2 mt-2 mt-md-0">
                                <i class="fas fa-clock ml-1"></i>
                                {{ $ticket->persianUpdatedAt('Y/m/d') }}
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="text-center pt-3">
            {{ $tickets->links() }}
        </div>
    @else
        <div class="alert alert-warning">
            @lang('title.nothing found')
        </div>
    @endif
    <div class="d-flex justify-content-end">
        <a href="{{ route('dashboard.tickets.create') }}" class="btn btn-primary">
            @lang('title.new_ticket')
            <i class="fa fa-plus-circle"></i>
        </a>
    </div>
@endsection
