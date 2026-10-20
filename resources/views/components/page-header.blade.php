@props(['judul', 'deskripsi' => null])

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ $judul }}</h1>
        @if ($deskripsi)
            <p class="text-body-secondary mb-0">{{ $deskripsi }}</p>
        @endif
    </div>

    <div>{{ $slot }}</div>
</div>