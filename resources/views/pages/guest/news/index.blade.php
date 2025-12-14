@extends('layouts.guest.app')

@section('content')
<!-- Breadcrumbs -->
<div class="breadcrumbs">
  <div class="container">
    <h2>Announcements & News</h2>
    <p>Stay updated with the latest news and announcements from our laboratory</p>
  </div>
</div>

<!-- Recent News and Announcements Section -->
<section id="recent-news" class="recent-news section">

  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <div class="row g-4">
      @if($allNews && $allNews->count() > 0)
        @foreach($allNews as $news)
        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
          <div class="post-box">
            <div class="post-img">
              <img src="{{ Vite::asset('resources/assets/guest/img/real-estate/property-exterior-3.webp') }}" class="img-fluid" alt="{{ e($news->judul) }}">
            </div>
            <div class="meta">
              <ul>
                <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <time datetime="{{ $news->created_at->format('Y-m-d') }}">{{ $news->created_at->format('M d, Y') }}</time></li>
                <li class="d-flex align-items-center"><i class="bi bi-chat-dots"></i> <a href="#">{{ ucfirst($news->jenis) }}</a></li>
              </ul>
            </div>
            <div class="d-flex align-items-start">
              <div class="flex-shrink-0 me-3">
                <img src="{{ $news->cover_image['url'] ?? Vite::asset('resources/assets/guest/img/person/person-m-10.webp') }}"
                     class="img-fluid rounded"
                     alt="{{ e($news->judul) }}"
                     style="width: 80px; height: 80px; object-fit: cover;"
                     onerror="this.onerror=null; this.src='{{ Vite::asset('resources/assets/guest/img/person/person-m-10.webp') }}';">
              </div>
              <div class="flex-grow-1">
                <h3 class="post-title">{{ e($news->judul) }}</h3>
                <div class="post-content">
                  <p>{!! Str::limit(strip_tags($news->isi), 120, '...') !!}</p>
                  <a href="{{ route('guest.news.show', $news) }}" class="readmore stretched-link">
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

    <!-- Pagination -->
    @if($allNews && $allNews->hasPages())
    <div class="row mt-5">
      <div class="col-12">
        <div class="d-flex justify-content-center">
          {{ $allNews->links() }}
        </div>
      </div>
    </div>
    @endif
  </div>
</section>
@endsection
