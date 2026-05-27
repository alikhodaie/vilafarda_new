<!-- Searchbard -->
<div class="single_widgets widget_search">
    <h4 class="title">@lang('title.search')</h4>
    <form action="{{ route('main.articles.index') }}" class="sidebar-search-form">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="@lang('title.search') ...">
        <button type="submit"><i class="ti-search"></i></button>
    </form>
</div>

@if($categories->isNotEmpty())
<!-- Categories -->
<div class="single_widgets widget_category">
    <h4 class="title">@lang('title.categories')</h4>
    <ul>
        @foreach($categories as $category)
            <li><a href="{{ route('main.articles.index', ['category' => $category->id]) }}">{{ $category->title }} <span>{{ $category->articles_count }}</span></a></li>
        @endforeach
    </ul>
</div>
@endif

<!-- Trending Posts -->
<div class="single_widgets widget_thumb_post">
    <h4 class="title">@lang('title.last_articles')</h4>
    <ul>
        @foreach($articles as $article)
            <li>
                <span class="left">
                    <img src="{{ $article->image_path }}" alt="{{ $article->title }}" class="">
                </span>
                <span class="right">
                    <a class="feed-title" href="{{ $article->link }}">{{ $article->title }}</a>
                    <span class="post-date"><i class="ti-calendar"></i>{{ $article->persianCreatedAt('d F Y') }}</span>
                </span>
            </li>
        @endforeach
    </ul>
</div>

@if($tags->isNotEmpty())
    <!-- Tags Cloud -->
    <div class="single_widgets widget_tags">
        <h4 class="title">@lang('title.tags')</h4>
        <ul>
            @foreach($tags as $tag)
                <li><a href="{{ route('main.articles.index', ['tag' => $tag->id]) }}">{{ $tag->name }}</a></li>
            @endforeach
        </ul>
    </div>
@endif
