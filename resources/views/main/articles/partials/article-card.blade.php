<div class="grid_blog_box">

    <div class="gtid_blog_thumb">
        <a href="{{ $article->link }}"><img src="{{ $article->image_path }}" class="img-fluid" alt="{{ $article->title }}" /></a>
        <div class="gtid_blog_info"><span>{{ $article->persianCreatedAt('d') }}</span>{{ $article->persianCreatedAt('F Y') }}</div>
    </div>

    <div class="blog-body">
        <h4 class="bl-title">
            <a href="{{ $article->link }}">{{ $article->title }}</a>
            @foreach($article->categories as $category)
                <a href="{{ route('main.articles.index', ['category' => $category->id]) }}" class="latest_new_post">{{ $category->title }}</a>
            @endforeach
        </h4>
        <p>{{ $article->summary }}</p>
    </div>

    <div class="modern_property_footer">
        <div class="property-author">
            <div class="path-img">
                <a href="{{ route('main.articles.index', ['author' => $article->author_id]) }}" tabindex="-1">
                    <img src="{{ $article->author->avatar_path }}" class="img-fluid rounded-circle" alt="{{ $article->author->full_name }}">
                </a>
            </div>
            <h5><a href="{{ route('main.articles.index', ['author' => $article->author_id]) }}" tabindex="-1">{{ $article->author->full_name }}</a></h5>
        </div>
        <span class="article-pulish-date"><i class="ti-comment-alt ml-2"></i>{{ $article->count_comments }}</span>
    </div>

</div>
