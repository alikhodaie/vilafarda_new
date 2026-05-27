@props(['home'])

@php
    $documents = ($home->relationLoaded('documents') ? $home->documents : $home->documents()->get()) ?? collect();
    if ($documents->isEmpty() && $home->document) {
        $legacyUrl = asset($home->getDocumentPath().$home->document);
        $documents = collect([(object) [
            'id' => 0,
            'name' => $home->document,
            'original_name' => $home->document,
            'url' => $legacyUrl,
            'is_image' => in_array(strtolower(pathinfo($home->document, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'heic', 'heif'], true),
            'is_pdf' => strtolower(pathinfo($home->document, PATHINFO_EXTENSION)) === 'pdf',
            'updated_at' => $home->updated_at,
        ]]);
    }
@endphp

@if($documents->isNotEmpty())
    <div class="col-12 mb-3">
        <label class="form-label fw-semibold mb-2 d-block">
            مدارک بارگذاری‌شده
            <span class="badge bg-secondary ms-1">{{ $documents->count() }}</span>
        </label>
        <div class="row g-3">
            @foreach($documents as $doc)
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 border shadow-sm">
                        <div class="card-body p-2 d-flex flex-column">
                            @if($doc->is_image)
                                <a href="{{ $doc->url }}" target="_blank" rel="noopener" class="d-block text-center mb-2 rounded bg-light p-2">
                                    <img src="{{ $doc->url }}?v={{ md5($doc->name.($doc->updated_at ?? '')) }}"
                                         alt="مدرک"
                                         class="img-fluid rounded"
                                         style="width: 50px; height: auto; max-height: 80px; object-fit: contain;"
                                         loading="lazy"
                                         decoding="async">
                                </a>
                            @elseif($doc->is_pdf)
                                <div class="mb-2 rounded border overflow-hidden bg-light">
                                    <iframe src="{{ $doc->url }}#toolbar=0" title="PDF" class="w-100" style="height: 120px; border: 0;"></iframe>
                                </div>
                            @else
                                <div class="text-center text-muted py-3 mb-2">
                                    <span class="fas fa-file-alt fa-2x d-block mb-2 opacity-50"></span>
                                    <span class="small text-break">{{ $doc->original_name ?: $doc->name }}</span>
                                </div>
                            @endif
                            <div class="mt-auto d-flex align-items-center justify-content-between gap-2 small">
                                <span class="text-truncate text-muted" title="{{ $doc->original_name ?: $doc->name }}">
                                    {{ $doc->original_name ?: $doc->name }}
                                </span>
                                <a href="{{ $doc->url }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary flex-shrink-0">
                                    <span class="fas fa-external-link-alt ms-1"></span>
                                    باز کردن
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
