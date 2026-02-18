@extends('layouts.public.app')

@section('content')

<!-- Hero Section -->
<section id="hero" class="hero section p-0 overflow-hidden">
    @if($slideshows->count() > 0)
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach($slideshows as $index => $slide)
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach($slideshows as $index => $slide)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}" style="height: 85vh; min-height: 500px;">
                        <img src="{{ $slide->image_url }}" class="d-block w-100 h-100" style="object-fit: cover; filter: brightness(0.6);" alt="{{ $slide->title }}">
                        <div class="carousel-caption d-none d-md-block text-start mb-5 pb-5">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-8" data-aos="fade-up">
                                        <h1 class="display-3 fw-bold text-white mb-3">{{ $slide->title }}</h1>
                                        <p class="lead text-white-50 mb-4 fs-4">{{ $slide->caption }}</p>
                                        @if($slide->link)
                                            <a href="{{ $slide->link }}" class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-lg transition-transform hover-scale">
                                                Explore More <i class="bi bi-arrow-right ms-2"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    @else
        <div class="container pt-5 mt-5" data-aos="fade-up" data-aos-delay="100">
            <div class="hero-content">
                <div class="row align-items-center">
                    <div class="col-lg-6 hero-text" data-aos="fade-right" data-aos-delay="200">
                        <div class="hero-badge">
                            <i class="bi bi-star-fill"></i>
                            <span>Premium Excellence</span>
                        </div>
                        <h1>Politeknik <br>Caltex Riau</h1>
                        <p>Empowering the next generation of industry leaders through innovative technology and hands-on education.</p>
                        <div class="cta-wrapper mt-4">
                            <a href="#" class="btn btn-primary btn-lg px-4 me-3">Apply Now</a>
                            <a href="#" class="btn btn-outline-light btn-lg px-4">Learn More</a>
                        </div>
                    </div>
                    <div class="col-lg-6 hero-images" data-aos="fade-left" data-aos-delay="400">
                        <div class="image-stack">
                            <div class="main-image">
                                <img src="{{ Vite::asset('resources/assets/public/img/real-estate/property-exterior-3.webp') }}" alt="Campus" class="img-fluid rounded shadow">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section><!-- /Hero Section -->

<style>
    .hover-scale:hover {
        transform: scale(1.05);
    }
    .transition-transform {
        transition: transform 0.3s ease-in-out;
    }
    .carousel-caption {
        bottom: 20%;
    }
    .carousel-indicators [data-bs-target] {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin: 0 6px;
    }
</style>

    <!-- Home Brief Section -->
    <section id="home-brief" class="home-brief section pb-0">
      <div class="container" data-aos="fade-up">
        <div class="row align-items-center gy-5">
          <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
            <div class="content-wrapper">
              <div class="section-badge mb-3">
                <i class="bi bi-info-circle"></i>
                <span>About Laboratory</span>
              </div>
              <h2 class="display-5 fw-bold mb-4">Pusat Layanan Laboratorium Terpadu</h2>
              <p class="lead mb-4 text-muted">Laboratorium Politeknik Caltex Riau menyediakan fasilitas modern dan layanan terpadu untuk mendukung kegiatan akademik, penelitian, dan pengabdian masyarakat.</p>
              
              <div class="row g-4 mb-5">
                <div class="col-sm-6">
                  <div class="p-3 border rounded shadow-sm hover-elevate">
                    <h4 class="fw-bold mb-1">Modern Labs</h4>
                    <p class="small text-muted mb-0">Fasilitas lab dengan peralatan standar industri.</p>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="p-3 border rounded shadow-sm hover-elevate">
                    <h4 class="fw-bold mb-1">Expert Staff</h4>
                    <p class="small text-muted mb-0">Dikelola oleh tenaga ahli yang berpengalaman.</p>
                  </div>
                </div>
              </div>

              <div class="cta-wrapper">
                <a href="#" class="btn btn-primary px-4 py-2 rounded-pill">Lihat Fasilitas</a>
                <a href="{{ route('public.request-software') }}" class="btn btn-outline-primary px-4 py-2 rounded-pill ms-3">Ajukan Software</a>
              </div>
            </div>
          </div>

          <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
            <img src="{{ Vite::asset('resources/assets/public/img/real-estate/property-exterior-7.webp') }}" alt="Laboratory" class="img-fluid rounded-4 shadow-lg">
          </div>
        </div>
      </div>
    </section><!-- /Home Brief Section -->

    <!-- FAQs Section -->
    <section id="faqs" class="faqs section pb-0">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>Frequently Asked Questions</h2>
          <p>Pertanyaan yang sering diajukan mengenai layanan laboratorium</p>
        </div>

        <div class="row justify-content-center">
          <div class="col-lg-10">
            @php $faqCount = 0; @endphp
            @foreach($faqs as $category => $categoryFaqs)
              <div class="mb-5" data-aos="fade-up">
                <h3 class="mb-4 border-bottom pb-2 text-primary">
                  <i class="bi bi-folder2-open me-2"></i> {{ $category ?: 'Umum' }}
                </h3>
                <div class="accordion accordion-flush shadow-sm rounded-4 overflow-hidden" id="faqAccordion-{{ Str::slug($category ?: 'umum') }}">
                  @foreach($categoryFaqs as $index => $faq)
                  <div class="accordion-item" data-aos="fade-up" data-aos-delay="{{ 100 * (++$faqCount) }}">
                    <h3 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-{{ $faq->id }}">
                        <i class="bi bi-question-circle me-2 text-primary"></i>
                        {{ $faq->question }}
                      </button>
                    </h3>
                    <div id="faq-content-{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion-{{ Str::slug($category ?: 'umum') }}">
                      <div class="accordion-body">
                        {!! $faq->answer !!}
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </section><!-- /FAQs Section -->

    <!-- Recent News and Announcements Section -->
    <section id="recent-blog-posts" class="recent-blog-posts section">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Recent News & Announcements</h2>
        <p>Stay updated with the latest news and announcements from our laboratory</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row g-4">
          @if($recentNews && $recentNews->count() > 0)
            @foreach($recentNews as $news)
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
              <div class="post-box">
                <div class="post-img">
                  <img src="{{ Vite::asset('resources/assets/public/img/real-estate/property-exterior-3.webp') }}" class="img-fluid" alt="{{ e($news->judul) }}">
                </div>
                <div class="meta">
                  <ul>
                    <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <time datetime="{{ $news->created_at->format('Y-m-d') }}">{{ $news->created_at->format('M d, Y') }}</time></li>
                    <li class="d-flex align-items-center"><i class="bi bi-chat-dots"></i> <a href="#">{{ ucfirst($news->jenis) }}</a></li>
                  </ul>
                </div>
                <div class="d-flex align-items-start">
                  <div class="flex-shrink-0 me-3">
                    <img src="{{ $news->cover_image['url'] ?? '' }}"
                         class="img-fluid rounded"
                         alt="{{ e($news->judul) }}"
                         style="width: 80px; height: 80px; object-fit: cover;"
                         onerror="this.onerror=null; this.src='{{ Vite::asset('resources/assets/public/img/person/person-m-10.webp') }}';">
                  </div>
                  <div class="flex-grow-1">
                    <h3 class="post-title">{{ e($news->judul) }}</h3>
                    <div class="post-content">
                      <p>{!! Str::limit(strip_tags($news->isi), 120, '...') !!}</p>
                      <a href="{{ route('public.news.show', $news) }}" class="readmore stretched-link">
                        <span>Read More</span>
                        <i class="bi bi-arrow-right"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          @else
            <div class="col-12">
              <div class="text-center py-5">
                <i class="bi bi-bell icon-lg mb-3"></i>
                <h4>No announcements or news available</h4>
                <p>Please check back later for updates.</p>
              </div>
            </div>
          @endif
        </div>
      </div>
    </section><!-- /Recent News and Announcements Section -->

    <!-- Approved Software Requests Section -->
    <section id="approved-software-requests" class="approved-software-requests section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Approved Software Requests</h2>
        <p>Software yang telah disetujui untuk digunakan di laboratorium</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row g-4">
          @if($approvedSoftwareRequests && $approvedSoftwareRequests->count() > 0)
            @foreach($approvedSoftwareRequests as $request)
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
              <div class="post-box">
                <div class="post-img">
                  <img src="{{ Vite::asset('resources/assets/public/img/real-estate/property-exterior-3.webp') }}" class="img-fluid" alt="{{ e($request->nama_software) }}">
                </div>
                <div class="meta">
                  <ul>
                    <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <time datetime="{{ $request->created_at->format('Y-m-d') }}">{{ $request->created_at->format('M d, Y') }}</time></li>
                    <li class="d-flex align-items-center"><i class="bi bi-chat-dots"></i>
                      @php
                          $badgeClass = '';
                          switch ($request->status) {
                              case 'menunggu_approval':
                                  $badgeClass = 'bg-warning';
                                  break;
                              case 'disetujui':
                                  $badgeClass = 'bg-success';
                                  break;
                              case 'ditolak':
                                  $badgeClass = 'bg-danger';
                                  break;
                              default:
                                  $badgeClass = 'bg-secondary';
                          }
                      @endphp
                      <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $request->status)) }}</span>
                    </li>
                  </ul>
                </div>
                <div class="d-flex align-items-start">
                  <div class="flex-shrink-0 me-3">
                    <i class="bi bi-software bx-lg text-primary" style="font-size: 3rem;"></i>
                  </div>
                  <div class="flex-grow-1">
                    <h3 class="post-title">{{ e($request->nama_software) }}</h3>
                    <div class="post-content">
                      <p>{!! Str::limit(strip_tags($request->alasan), 120, '...') !!}</p>

                      @if($request->mataKuliahs->count() > 0)
                        <div class="mt-2">
                          <strong>Mata Kuliah:</strong>
                          @foreach($request->mataKuliahs->take(3) as $mk)
                            <span class="badge bg-label-primary me-1">{{ $mk->kode }} - {{ $mk->nama }}</span>
                          @endforeach
                          @if($request->mataKuliahs->count() > 3)
                            <span class="text-muted">+{{ $request->mataKuliahs->count() - 3 }} lainnya</span>
                          @endif
                        </div>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          @else
            <div class="col-12">
              <div class="text-center py-5">
                <i class="bi bi-software icon-lg mb-3"></i>
                <h4>Tidak ada software yang disetujui</h4>
                <p>Belum ada software yang disetujui untuk digunakan di laboratorium.</p>
                <a href="{{ route('public.request-software') }}" class="btn btn-primary">
                  <i class="bx bx-software me-1"></i> Ajukan Software
                </a>
              </div>
            </div>
          @endif
        </div>
      </div>
    </section><!-- /Approved Software Requests Section -->

    <!-- Call To Action Section -->
    <section class="call-to-action section" id="call-to-action">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row align-items-center justify-content-center text-center">
          <div class="col-lg-8">
            <div class="cta-content p-5 bg-primary text-white rounded-4 shadow-lg">
              <h2 class="mb-3 text-white">Butuh Bantuan atau Informasi Lebih Lanjut?</h2>
              <p class="mb-4 text-white-50">Tim admin kami siap membantu Anda dalam proses pendaftaran, penggunaan laboratorium, dan layanan lainnya.</p>
              <div class="cta-actions">
                <a href="#" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill shadow-lg transition-transform hover-scale">Hubungi Kami</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section><!-- /Call To Action Section -->

@endsection

