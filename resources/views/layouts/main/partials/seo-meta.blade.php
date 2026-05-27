@php
    $seo = isset($seo) && is_array($seo) ? $seo : [];
    $description = $seo['description'] ?? '';
    $canonical = $seo['canonical'] ?? url(request()->path());
    $robots = $seo['robots'] ?? 'index, follow';
    $og = $seo['og'] ?? [];
    $twitter = $seo['twitter'] ?? [];
    $keywords = $seo['keywords'] ?? [];
    $author = $seo['author'] ?? null;
    $pageTitle = $documentTitle ?? $seo['document_title'] ?? config('app.name');
    $ogTitle = $og['title'] ?? $pageTitle;
    $ogDescription = $og['description'] ?? $description;
    $ogImage = $og['image'] ?? null;
    $ogUrl = $og['url'] ?? $canonical;
    $ogType = $og['type'] ?? 'website';
    $googleVerification = trim((string) setting('seo:google-site-verification', ''));
@endphp

@if($description !== '')
    <meta name="description" content="{{ $description }}">
@endif

<link rel="canonical" href="{{ $canonical }}">

<meta name="robots" content="{{ $robots }}">

@if($author)
    <meta name="author" content="{{ $author }}">
@endif

@foreach((array) $keywords as $keyword)
    @if(filled($keyword))
        <meta name="keywords" content="{{ $keyword }}">
    @endif
@endforeach

<meta property="og:locale" content="fa_IR">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:title" content="{{ $ogTitle }}">
@if($ogDescription !== '')
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($ogDescription), 300) }}">
@endif
@if($ogImage)
    <meta property="og:image" content="{{ $ogImage }}">
@endif
<meta property="og:url" content="{{ $ogUrl }}">
<meta property="og:type" content="{{ $ogType }}">

@if(!empty($twitter))
    <meta name="twitter:card" content="{{ $twitter['card'] ?? 'summary_large_image' }}">
    @if(!empty($twitter['title']))
        <meta name="twitter:title" content="{{ $twitter['title'] }}">
    @endif
    @if(!empty($twitter['description']))
        <meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($twitter['description']), 300) }}">
    @endif
    @if(!empty($twitter['image']))
        <meta name="twitter:image" content="{{ $twitter['image'] }}">
    @endif
@endif

@if($googleVerification !== '')
    <meta name="google-site-verification" content="{{ $googleVerification }}">
@endif

@include('layouts.main.partials.json-ld')
