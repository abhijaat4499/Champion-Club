@extends('layouts.admin-panel')

@section('title', 'Gallery')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Gallery" icon="gallery">
        <a href="{{ route('gym.gallery') }}" class="btn-logout" target="_blank" rel="noopener">View on website</a>
        <a href="{{ route('admin.gallery.create') }}" class="btn-admin-small">+ Upload photos</a>
    </x-admin.page-header>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <p class="settings-note">Photos shown on the Gallery page. Drag cards to reorder. Descriptive alt text helps image SEO.</p>

    @if($photos->isEmpty())
        <div class="table-empty gallery-empty">No photos yet. Upload your first photos to show them on the website.</div>
    @else
        <form method="POST" action="{{ route('admin.gallery.bulk-destroy') }}" id="bulkForm" onsubmit="return confirm('Delete the selected photos?');">
            @csrf
            @method('DELETE')

            <div class="gallery-toolbar">
                <label class="gallery-select-all">
                    <input type="checkbox" id="selectAll"> Select all
                </label>
                <button type="submit" class="btn-delete" id="bulkDelete" disabled>Delete selected</button>
                <span class="gallery-save-state" id="orderState" aria-live="polite"></span>
            </div>

            <div class="gallery-admin-grid" id="galleryGrid" data-reorder-url="{{ route('admin.gallery.reorder') }}">
                @foreach($photos as $photo)
                    <article class="gallery-card" draggable="true" data-id="{{ $photo->id }}">
                        <div class="gallery-card-media">
                            <img src="{{ $photo->url() }}" alt="{{ $photo->alt }}" loading="lazy">
                            @unless($photo->is_active)
                                <span class="status-chip status-hidden gallery-card-status">Hidden</span>
                            @endunless
                        </div>
                        <div class="gallery-card-body">
                            <div class="gallery-card-title">
                                <span class="gallery-drag" aria-hidden="true">⋮⋮</span>
                                <input type="checkbox" name="ids[]" value="{{ $photo->id }}" class="gallery-check" aria-label="Select photo {{ $loop->iteration }}">
                                <span class="gallery-alt" title="{{ $photo->alt }}">{{ $photo->alt }}</span>
                            </div>
                            <p class="gallery-caption-text">{{ $photo->caption ?: '—' }}</p>
                            <div class="gallery-card-foot">
                                <span class="gallery-order" data-order>#{{ $loop->iteration }}</span>
                                <div class="row-actions">
                                    <a href="{{ route('admin.gallery.edit', $photo) }}" class="btn-edit">Edit</a>
                                    <button type="submit" form="delete-{{ $photo->id }}" class="btn-delete">Delete</button>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </form>

        @foreach($photos as $photo)
            <form method="POST" action="{{ route('admin.gallery.destroy', $photo) }}" id="delete-{{ $photo->id }}" onsubmit="return confirm('Delete this photo?');" hidden>
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    @endif
</div>

<script>
    (function () {
        const grid = document.getElementById('galleryGrid');
        if (!grid) return;

        const selectAll = document.getElementById('selectAll');
        const bulkDelete = document.getElementById('bulkDelete');
        const state = document.getElementById('orderState');
        const checks = () => [...grid.querySelectorAll('.gallery-check')];

        const syncBulk = () => {
            const selected = checks().filter((c) => c.checked).length;
            bulkDelete.disabled = selected === 0;
            bulkDelete.textContent = selected ? `Delete selected (${selected})` : 'Delete selected';
            selectAll.checked = selected > 0 && selected === checks().length;
        };

        selectAll.addEventListener('change', () => {
            checks().forEach((c) => { c.checked = selectAll.checked; });
            syncBulk();
        });
        grid.addEventListener('change', (e) => { if (e.target.classList.contains('gallery-check')) syncBulk(); });

        let dragged = null;

        grid.addEventListener('dragstart', (e) => {
            dragged = e.target.closest('.gallery-card');
            if (!dragged) return;
            dragged.classList.add('is-dragging');
            e.dataTransfer.effectAllowed = 'move';
        });

        grid.addEventListener('dragover', (e) => {
            e.preventDefault();
            const over = e.target.closest('.gallery-card');
            if (!dragged || !over || over === dragged) return;
            const rect = over.getBoundingClientRect();
            const after = (e.clientY - rect.top) > rect.height / 2 || (e.clientX - rect.left) > rect.width / 2;
            grid.insertBefore(dragged, after ? over.nextSibling : over);
        });

        grid.addEventListener('dragend', () => {
            if (!dragged) return;
            dragged.classList.remove('is-dragging');
            dragged = null;
            save();
        });

        function save() {
            const cards = [...grid.querySelectorAll('.gallery-card')];
            cards.forEach((card, i) => { card.querySelector('[data-order]').textContent = '#' + (i + 1); });
            state.textContent = 'Saving order…';

            fetch(grid.dataset.reorderUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('#bulkForm input[name="_token"]').value,
                },
                body: JSON.stringify({ ids: cards.map((card) => Number(card.dataset.id)) }),
            })
                .then((r) => { state.textContent = r.ok ? 'Order saved' : 'Could not save order'; })
                .catch(() => { state.textContent = 'Could not save order'; });
        }
    })();
</script>
@endsection
