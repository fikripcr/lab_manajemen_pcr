@if(request()->ajax() || request()->has('ajax'))
    <div class="modal-header">
        <h5 class="modal-title">Documentation: {{ $fileName }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="documentation-content p-2" id="doc-content-modal" style="max-height: 400px; overflow-y: auto;">
            {!! $htmlContent !!}
        </div>
        <div class="text-muted small mt-3 border-top pt-2">
            Last updated: {{ formatTanggalIndo($lastUpdated) }}
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Close</button>
        <div class="d-flex gap-2">
            <x-tabler.button :href="route('sys.documentation.edit', $fileName)" class="btn-outline-primary" icon="ti ti-pencil" text="Edit" />
            <x-tabler.button :href="route('sys.documentation.show', $fileName)" class="btn-primary" icon="ti ti-external-link" text="View Full Page" />
        </div>
    </div>
@else
    @extends('layouts.sys.app')

    @section('title', 'Documentation: ' . $fileName)

    @section('content')
        @push('css')
            <link rel="stylesheet" href="{{ Vite::asset('resources/assets/sys/css/documentation-show.css') }}">
        @endpush

        <div class="row">
            <div class="col-md-3 ">
                <div class=" sticky-top">
                    <div class="mb-1">
                        <x-tabler.button type="back" :href="route('sys.documentation.index')" class="w-100" />
                    </div>

                    <div class="card" style="top: 1rem;">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 text-primary"><i class="bx bx-list-ul me-2"></i>Table of Contents</h5>
                            <div>
                                <x-tabler.button type="button" class="btn-sm btn-outline-primary me-2" icon="bx bx-edit" :href="route('sys.documentation.edit', $fileName)" text="Ubah" />
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <nav id="toc-nav" class="nav flex-column">
                                <!-- TOC akan di-generate oleh JS -->
                            </nav>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <small class="text-muted">Last updated: {{ formatTanggalIndo($lastUpdated) }}</small>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <div class="documentation-content" id="doc-content">
                            {!! $htmlContent !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const docContent = document.getElementById('doc-content');
                const tocNav = document.getElementById('toc-nav');

                // Ambil semua heading (h1, h2, h3) untuk TOC
                if(docContent && tocNav) {
                    const headings = Array.from(docContent.querySelectorAll('h1, h2, h3'));

                    // Hapus TOC lama jika ada
                    tocNav.innerHTML = '';

                    // Buat TOC dinamis dari heading
                    headings.forEach(heading => {
                        const level = parseInt(heading.tagName.charAt(1));
                        const id = heading.id || heading.textContent.trim().toLowerCase().replace(/\s+/g, '-').replace(/[^\w-]/g, '');

                        // Beri ID jika belum ada
                        if (!heading.id) {
                            heading.id = id;
                        }

                        const link = document.createElement('a');
                        link.href = '#' + id;
                        link.className = 'nav-link';
                        link.dataset.depth = level;
                        link.textContent = heading.textContent;

                        // Tambahkan event listener untuk scroll smooth
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            const target = document.getElementById(id);
                            if (target) {
                                window.scrollTo({
                                    top: target.offsetTop + 80, // Offset header
                                    behavior: 'smooth'
                                });
                            }
                        });

                        tocNav.appendChild(link);
                    });

                    // Fungsi untuk update highlight TOC saat scroll
                    let currentActive = null;

                    window.addEventListener('scroll', function() {
                        let found = false;

                        for (let i = headings.length - 1; i >= 0; i--) {
                            const heading = headings[i];
                            const rect = heading.getBoundingClientRect();

                            // Jika heading sudah terlihat di atas setengah layar
                            if (rect.top <= window.innerHeight / 2) {
                                const link = tocNav.querySelector(`a[href="#${heading.id}"]`);

                                if (currentActive) {
                                    currentActive.classList.remove('active');
                                }

                                if (link) {
                                    link.classList.add('active');
                                    currentActive = link;
                                }

                                found = true;
                                break;
                            }
                        }

                        // Jika tidak ada yang cocok, hilangkan active
                        if (!found && currentActive) {
                            currentActive.classList.remove('active');
                            currentActive = null;
                        }
                    });

                    // Trigger scroll event pertama kali
                    window.dispatchEvent(new Event('scroll'));
                }
            });
        </script>
    @endpush
@endif
