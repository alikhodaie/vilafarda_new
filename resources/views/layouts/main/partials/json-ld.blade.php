@if(!empty($jsonLd) && is_array($jsonLd))
    <script type="application/ld+json">@json($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG)</script>
@endif
