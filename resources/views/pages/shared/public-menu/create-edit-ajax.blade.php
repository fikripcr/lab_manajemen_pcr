<x-tabler.form-modal
    :title="$menu->exists ? 'Edit Menu' : 'Tambah Menu'"
    :route="$menu->exists ? route('shared.public-menu.update', $menu->hashid) : route('shared.public-menu.store')"
    :method="$menu->exists ? 'PUT' : 'POST'"
>
    
    <div class="mb-3">
        <label class="form-label">Parent Menu</label>
        <select class="form-select" name="parent_id">
            <option value="">-- Menu Utama (Root) --</option>
            @foreach($parents as $parent)
                <option value="{{ $parent->hashid }}" {{ (old('parent_id') ?? ($menu->parent_id ? $menu->parent->hashid : '')) == $parent->hashid ? 'selected' : '' }}>
                    {{ $parent->title }}
                </option>
            @endforeach
        </select>
    </div>

    <x-tabler.form-input 
        name="title" 
        label="Judul Menu" 
        :value="$menu->title"
        required
    />

    <div class="mb-3">
        <label class="form-label required">Tipe Menu</label>
        <select class="form-select" name="type" id="menu-type-select">
            <option value="url" {{ $menu->type == 'url' ? 'selected' : '' }}>URL Eksternal / Link Biasa</option>
            <option value="page" {{ $menu->type == 'page' ? 'selected' : '' }}>Halaman CMS (Public Page)</option>
            <option value="route" {{ $menu->type == 'route' ? 'selected' : '' }}>Route Internal App</option>
        </select>
    </div>

    <div id="type-url-group" class="mb-3" style="display: none;">
        <x-tabler.form-input 
            name="url" 
            label="URL / Link" 
            :value="$menu->url"
            placeholder="https://example.com atau /path/to/link"
        />
    </div>

    <div id="type-page-group" class="mb-3" style="display: none;">
        <label class="form-label required">Pilih Halaman</label>
        <select class="form-select" name="page_id">
            <option value="">-- Pilih Halaman --</option>
            @foreach($pages as $page)
                <option value="{{ $page->hashid }}" {{ $menu->page_id == $page->page_id ? 'selected' : '' }}>
                    {{ $page->title }}
                </option>
            @endforeach
        </select>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label required">Posisi</label>
                <select class="form-select" name="position">
                    <option value="header" {{ $menu->position == 'header' ? 'selected' : '' }}>Header (Navbar)</option>
                    <option value="footer_col_1" {{ $menu->position == 'footer_col_1' ? 'selected' : '' }}>Footer Kolom 1</option>
                    <option value="footer_col_2" {{ $menu->position == 'footer_col_2' ? 'selected' : '' }}>Footer Kolom 2</option>
                    <option value="footer_col_3" {{ $menu->position == 'footer_col_3' ? 'selected' : '' }}>Footer Kolom 3</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
             <div class="mb-3">
                <label class="form-label required">Target</label>
                <select class="form-select" name="target">
                    <option value="_self" {{ $menu->target == '_self' ? 'selected' : '' }}>Tab Sama (_self)</option>
                    <option value="_blank" {{ $menu->target == '_blank' ? 'selected' : '' }}>Tab Baru (_blank)</option>
                </select>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" {{ $menu->exists ? ($menu->is_active ? 'checked' : '') : 'checked' }}>
            <span class="form-check-label">Menu Aktif</span>
        </label>
    </div>

    <script>
        // Inline script to handle type change (re-run via Xhr or use global delegated listener if preferred)
        // Since this is loaded via AJAX modal, we execute immediately
        (function() {
            const typeSelect = document.getElementById('menu-type-select');
            const urlGroup = document.getElementById('type-url-group');
            const pageGroup = document.getElementById('type-page-group');
            
            function toggleFields() {
                const val = typeSelect.value;
                if (val === 'page') {
                    urlGroup.style.display = 'none';
                    pageGroup.style.display = 'block';
                } else {
                    urlGroup.style.display = 'block';
                    pageGroup.style.display = 'none';
                    // Change label based on route or url
                    const urlLabel = urlGroup.querySelector('label');
                    if(val === 'route') {
                        urlLabel.innerText = "Nama Route (contoh: login)";
                    } else {
                        urlLabel.innerText = "URL / Link";
                    }
                }
            }

            if(typeSelect) {
                typeSelect.addEventListener('change', toggleFields);
                toggleFields(); // Init
            }
        })();
    </script>

</x-tabler.form-modal>
