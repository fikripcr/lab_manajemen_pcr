@extends('layouts.guest.app')

@section('content')
<!-- Breadcrumbs -->
<div class="breadcrumbs">
  <div class="container">
    <ol class="breadcrumb breadcrumb-arranged flex-lg-row flex-column justify-content-lg-start justify-content-center align-items-lg-center align-items-center">
      <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
      <li class="breadcrumb-item active">{{ $pengumuman->judul }}</li>
    </ol>
  </div>
</div>

<!-- Blog Details Section -->
<section class="blog-details section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <article>
          <div class="post">

            <div class="post-img">
              <img src="{{ Vite::asset('resources/assets/guest/img/real-estate/property-exterior-3.webp') }}" alt="{{ e($pengumuman->judul) }}" class="img-fluid">
            </div>

            <div class="meta-top d-flex">
              <ul class="list-unstyled d-flex flex-wrap align-items-center">
                <li class="d-flex align-items-center me-4"><i class="bi bi-person"></i> <a href="#">{{ $pengumuman->penulis ? $pengumuman->penulis->name : 'System' }}</a></li>
                <li class="d-flex align-items-center me-4"><i class="bi bi-clock"></i> <time datetime="{{ $pengumuman->created_at->format('Y-m-d') }}">{{ $pengumuman->created_at->format('M d, Y') }}</time></li>
                <li class="d-flex align-items-center"><i class="bi bi-chat-dots"></i> <a href="#">{{ ucfirst($pengumuman->jenis) }}</a></li>
              </ul>
            </div>

            <h1 class="mb-4">{{ e($pengumuman->judul) }}</h1>

            <div class="content">
              <div>
                {!! $pengumuman->isi !!}
              </div>
            </div>

            <div class="post-tags d-flex justify-content-between align-items-center">
              <div class="tags">
                <span class="badge bg-primary">{{ ucfirst($pengumuman->jenis) }}</span>
                @if($pengumuman->is_published)
                  <span class="badge bg-success">Published</span>
                @else
                  <span class="badge bg-warning">Draft</span>
                @endif
              </div>
              <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
            </div>

          </div>
        </article>
      </div>
    </div>
  </div>
</section><!-- /Blog Details Section -->

<!-- Comments Section -->
<section class="blog-comments section bg-gray-light">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="comments">
          <h4 class="comments-title mb-4">Comments (0)</h4>
          <p>No comments yet. Be the first to share your thoughts!</p>
        </div>
      </div>
    </div>
  </div>
</section><!-- /Comments Section -->
@endsection
