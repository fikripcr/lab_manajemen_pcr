@extends('layouts.admin.app')

@section('title', 'System Documentation')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Sidebar TOC -->
        <div class="col-md-3 col-lg-2 d-none d-md-block">
            <div class="card sticky-top" style="top: 1rem;">
                <div class="card-header">
                    <h6 class="mb-0">Table of Contents</h6>
                </div>
                <div class="card-body p-0">
                    <nav id="toc-nav" class="nav flex-column">
                        <!-- TOC akan di-generate oleh JS -->
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Laravel Base Template - Development Guidelines</h4>
                    <small class="text-muted">Last updated: {{ \Carbon\Carbon::parse($lastUpdated)->format('d M Y H:i') }}</small>
                </div>
                <div class="card-body">
                    <div class="documentation-content" id="doc-content">
                        {!! $htmlContent !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.documentation-content {
    max-width: 100%;
    overflow-x: auto;
}
.documentation-content h1 {
    font-size: 2rem;
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    color: #333;
    border-bottom: 2px solid #dee2e6;
    padding-bottom: 0.5rem;
}
.documentation-content h2 {
    font-size: 1.75rem;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    color: #444;
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 0.5rem;
}
.documentation-content h3 {
    font-size: 1.5rem;
    margin-top: 1.25rem;
    margin-bottom: 0.5rem;
    color: #555;
}
.documentation-content p {
    margin-bottom: 1rem;
    line-height: 1.7;
}
.documentation-content ul {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}
.documentation-content li {
    margin-bottom: 0.5rem;
    line-height: 1.6;
}
.documentation-content pre.code-block {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.375rem;
    padding: 1rem;
    overflow-x: auto;
    margin: 1rem 0;
}
.documentation-content code.inline-code {
    background-color: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
}
.documentation-content a {
    color: #0d6efd;
    text-decoration: none;
}
.documentation-content a:hover {
    color: #0a58ca;
    text-decoration: underline;
}

/* Style untuk TOC */
#toc-nav .nav-link {
    padding: 0.5rem 1rem;
    border-left: 3px solid transparent;
    color: #495057;
    transition: all 0.2s ease;
}
#toc-nav .nav-link:hover {
    color: #0d6efd;
    background-color: #f8f9fa;
}
#toc-nav .nav-link.active {
    color: #0d6efd;
    border-left-color: #0d6efd;
    background-color: #e7f1ff;
    font-weight: 500;
}
#toc-nav .nav-link[data-depth="1"] {
    font-weight: 600;
    padding-left: 0.75rem;
}
#toc-nav .nav-link[data-depth="2"] {
    padding-left: 1.5rem;
    font-size: 0.95rem;
}
#toc-nav .nav-link[data-depth="3"] {
    padding-left: 2.25rem;
    font-size: 0.9rem;
}

/* Sticky card di sidebar */
.sticky-top {
    position: -webkit-sticky;
    position: sticky;
    top: 1rem;
    z-index: 1000;
}

/* Responsive: sembunyikan sidebar di mobile */
@media (max-width: 767.98px) {
    .d-md-block {
        display: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const docContent = document.getElementById('doc-content');
    const tocNav = document.getElementById('toc-nav');

    // Ambil semua heading (h1, h2, h3)
    const headings = Array.from(docContent.querySelectorAll('h2'));

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
                    top: target.offsetTop - 100, // Offset header
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
});
</script>
@endsection
