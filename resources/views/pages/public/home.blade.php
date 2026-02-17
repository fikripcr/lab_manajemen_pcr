@extends('layouts.public.app')

@section('content')

<!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="hero-content">
          <div class="row align-items-center">

            <div class="col-lg-6 hero-text" data-aos="fade-right" data-aos-delay="200">
              <div class="hero-badge">
                <i class="bi bi-star-fill"></i>
                <span>Premium Properties</span>
              </div>
              <h1>Laravel <br>Politeknik Caltex Riau</h1>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Browse thousands of verified listings from trusted agents.</p>

              <div class="search-form" data-aos="fade-up" data-aos-delay="300">
                <form action="">
                  <div class="row g-3">
                    <div class="col-12">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="location" name="location" required="">
                        <label for="location">Location</label>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-floating">
                        <select class="form-select" id="property-type" name="property_type" required="">
                          <option value="">Select Type</option>
                          <option value="house">House</option>
                          <option value="apartment">Apartment</option>
                          <option value="condo">Condo</option>
                          <option value="townhouse">Townhouse</option>
                          <option value="land">Land</option>
                        </select>
                        <label for="property-type">Property Type</label>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-floating">
                        <select class="form-select" id="price-range" name="price_range" required="">
                          <option value="">Price Range</option>
                          <option value="0-200000">Under $200K</option>
                          <option value="200000-500000">$200K - $500K</option>
                          <option value="500000-800000">$500K - $800K</option>
                          <option value="800000-1200000">$800K - $1.2M</option>
                          <option value="1200000+">Above $1.2M</option>
                        </select>
                        <label for="price-range">Price Range</label>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-floating">
                        <select class="form-select" id="bedrooms" name="bedrooms">
                          <option value="">Bedrooms</option>
                          <option value="1">1 Bedroom</option>
                          <option value="2">2 Bedrooms</option>
                          <option value="3">3 Bedrooms</option>
                          <option value="4">4 Bedrooms</option>
                          <option value="5+">5+ Bedrooms</option>
                        </select>
                        <label for="bedrooms">Bedrooms</label>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-floating">
                        <select class="form-select" id="bathrooms" name="bathrooms">
                          <option value="">Bathrooms</option>
                          <option value="1">1 Bathroom</option>
                          <option value="2">2 Bathrooms</option>
                          <option value="3">3 Bathrooms</option>
                          <option value="4+">4+ Bathrooms</option>
                        </select>
                        <label for="bathrooms">Bathrooms</label>
                      </div>
                    </div>

                    <div class="col-12">
                      <button type="submit" class="btn btn-search w-100">
                        <i class="bi bi-search"></i>
                        Search Properties
                      </button>
                    </div>
                  </div>
                </form>
              </div>

              <div class="hero-stats" data-aos="fade-up" data-aos-delay="400">
                <div class="row">
                  <div class="col-4">
                    <div class="stat-item">
                      <h3><span data-purecounter-start="0" data-purecounter-end="2847" data-purecounter-duration="1" class="purecounter"></span>+</h3>
                      <p>Properties Listed</p>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="stat-item">
                      <h3><span data-purecounter-start="0" data-purecounter-end="156" data-purecounter-duration="1" class="purecounter"></span>+</h3>
                      <p>Verified Agents</p>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="stat-item">
                      <h3><span data-purecounter-start="0" data-purecounter-end="98" data-purecounter-duration="1" class="purecounter"></span>%</h3>
                      <p>Client Satisfaction</p>
                    </div>
                  </div>
                </div>
              </div>

            </div><!-- End Hero Text -->

            <div class="col-lg-6 hero-images" data-aos="fade-left" data-aos-delay="400">
              <div class="image-stack">
                <div class="main-image">
                  <img src="{{ Vite::asset('resources/assets/guest/img/real-estate/property-exterior-3.webp') }}" alt="Luxury Property" class="img-fluid">
                  <div class="property-tag">
                    <span class="price">$850,000</span>
                    <span class="type">Featured</span>
                  </div>
                </div>

                <div class="secondary-image">
                  <img src="{{ Vite::asset('resources/assets/guest/img/real-estate/property-interior-7.webp') }}" alt="Property Interior" class="img-fluid">
                </div>

                <div class="floating-card">
                  <div class="agent-info">
                    <img src="{{ Vite::asset('resources/assets/guest/img/real-estate/agent-4.webp') }}" alt="Agent" class="agent-avatar">
                    <div class="agent-details">
                      <h5>Sarah Johnson</h5>
                      <p>Top Real Estate Agent</p>
                      <div class="rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <span>4.9 (127 reviews)</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Hero Images -->

          </div>
        </div>

      </div>

    </section><!-- /Hero Section -->

    <!-- Home About Section -->
    <section id="home-about" class="home-about section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center gy-5">

          <div class="col-lg-6 order-lg-2" data-aos="fade-left" data-aos-delay="200">
            <div class="image-section">
              <div class="main-image-wrapper">
                <img src="{{ Vite::asset('resources/assets/guest/img/real-estate/property-exterior-7.webp') }}" alt="Premium Property" class="img-fluid main-image">
                <div class="floating-card">
                  <div class="card-content">
                    <div class="icon">
                      <i class="bi bi-award"></i>
                    </div>
                    <div class="text">
                      <span class="number"><span data-purecounter-start="0" data-purecounter-end="12" data-purecounter-duration="1" class="purecounter"></span>+</span>
                      <span class="label">Awards Won</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="secondary-images">
                <div class="small-image">
                  <img src="{{ Vite::asset('resources/assets/guest/img/real-estate/property-interior-8.webp') }}" alt="Interior Design" class="img-fluid">
                </div>
                <div class="small-image">
                  <img src="{{ Vite::asset('resources/assets/guest/img/real-estate/agent-3.webp') }}" alt="Expert Agent" class="img-fluid">
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6 order-lg-1" data-aos="fade-right" data-aos-delay="300">
            <div class="content-wrapper">
              <div class="section-badge">
                <i class="bi bi-buildings"></i>
                <span>Premium Real Estate</span>
              </div>

              <h2>Transforming Real Estate Dreams Into Reality</h2>

              <p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore dolore magna aliqua. Enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip consequat.</p>

              <div class="stats-grid">
                <div class="stat-item" data-aos="zoom-in" data-aos-delay="400">
                  <div class="stat-number">
                    <span data-purecounter-start="0" data-purecounter-end="2800" data-purecounter-duration="2" class="purecounter"></span>+
                  </div>
                  <div class="stat-label">Properties Listed</div>
                </div>

                <div class="stat-item" data-aos="zoom-in" data-aos-delay="450">
                  <div class="stat-number">
                    <span data-purecounter-start="0" data-purecounter-end="95" data-purecounter-duration="1" class="purecounter"></span>%
                  </div>
                  <div class="stat-label">Success Rate</div>
                </div>

                <div class="stat-item" data-aos="zoom-in" data-aos-delay="500">
                  <div class="stat-number">
                    <span data-purecounter-start="0" data-purecounter-end="24" data-purecounter-duration="1" class="purecounter"></span>/7
                  </div>
                  <div class="stat-label">Client Support</div>
                </div>
              </div>

              <div class="features-list">
                <div class="feature-item">
                  <i class="bi bi-check-circle"></i>
                  <span>Expert market analysis and pricing strategies</span>
                </div>
                <div class="feature-item">
                  <i class="bi bi-check-circle"></i>
                  <span>Personalized property matching services</span>
                </div>
                <div class="feature-item">
                  <i class="bi bi-check-circle"></i>
                  <span>Professional photography and virtual tours</span>
                </div>
              </div>

              <div class="cta-wrapper">
                <a href="about.html" class="btn-primary">
                  <span>Learn More About Us</span>
                  <i class="bi bi-arrow-right-circle"></i>
                </a>
                <div class="contact-quick">
                  <i class="bi bi-headset"></i>
                  <div class="contact-text">
                    <span>Need assistance?</span>
                    <a href="tel:+15559876543">+1 (555) 987-6543</a>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>

    </section><!-- /Home About Section -->

    <!-- Featured Properties Section -->
    <section id="featured-properties" class="featured-properties section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Featured Properties</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="grid-featured" data-aos="zoom-in" data-aos-delay="150">

          <article class="highlight-card">
            <div class="media">
              <div class="badge-set">
                <span class="flag featured">Featured</span>
                <span class="flag premium">Premium</span>
              </div>
              <a href="property-details.html" class="image-link">
                <img src="{{ Vite::asset('resources/assets/guest/img/real-estate/property-exterior-6.webp') }}" alt="Showcase Villa" class="img-fluid">
              </a>
              <div class="quick-specs">
                <span><i class="bi bi-door-open"></i> 5 Beds</span>
                <span><i class="bi bi-droplet"></i> 4 Baths</span>
                <span><i class="bi bi-aspect-ratio"></i> 4,900 sq ft</span>
              </div>
            </div>
            <div class="content">
              <div class="top">
                <div>
                  <h3><a href="property-details.html">Seaside Villa with Infinity Pool</a></h3>
                  <div class="loc"><i class="bi bi-geo-alt-fill"></i> Coronado, CA 92118</div>
                </div>
                <div class="price">$3,760,000</div>
              </div>
              <p class="excerpt">Praesent commodo cursus magna, fusce dapibus tellus ac cursus commodo, vestibulum id ligula porta felis euismod semper.</p>
              <div class="cta">
                <a href="property-details.html" class="btn-main">Arrange Visit</a>
                <a href="property-details.html" class="btn-soft">More Photos</a>
                <div class="meta">
                  <span class="status for-sale">For Sale</span>
                  <span class="listed">Listed 2 days ago</span>
                </div>
              </div>
            </div>
          </article><!-- End Highlight Card -->

          <div class="mini-list">

            <article class="mini-card" data-aos="fade-up" data-aos-delay="200">
              <a href="property-details.html" class="thumb">
                <img src="{{ Vite::asset('resources/assets/guest/img/real-estate/property-interior-2.webp') }}" alt="Loft Haven" class="img-fluid" loading="lazy">
                <span class="label hot"><i class="bi bi-lightning-charge-fill"></i> Hot</span>
              </a>
              <div class="mini-body">
                <h4><a href="property-details.html">Urban Loft with Skyline Views</a></h4>
                <div class="mini-loc"><i class="bi bi-geo"></i> Denver, CO 80203</div>
                <div class="mini-specs">
                  <span><i class="bi bi-door-open"></i> 2</span>
                  <span><i class="bi bi-droplet"></i> 2</span>
                  <span><i class="bi bi-rulers"></i> 1,450 sq ft</span>
                </div>
                <div class="mini-foot">
                  <div class="mini-price">$689,000</div>
                  <a href="property-details.html" class="mini-btn">Details</a>
                </div>
              </div>
            </article><!-- End Mini Card -->

            <article class="mini-card" data-aos="fade-up" data-aos-delay="250">
              <a href="property-details.html" class="thumb">
                <img src="{{ Vite::asset('resources/assets/guest/img/real-estate/property-exterior-3.webp') }}" alt="Suburban Home" class="img-fluid" loading="lazy">
                <span class="label new"><i class="bi bi-star-fill"></i> New</span>
              </a>
              <div class="mini-body">
                <h4><a href="property-details.html">Charming Suburban Retreat</a></h4>
                <div class="mini-loc"><i class="bi bi-geo"></i> Austin, TX 78745</div>
                <div class="mini-specs">
                  <span><i class="bi bi-door-open"></i> 4</span>
                  <span><i class="bi bi-droplet"></i> 3</span>
                  <span><i class="bi bi-rulers"></i> 2,350 sq ft</span>
                </div>
                <div class="mini-foot">
                  <div class="mini-price">$545,000</div>
                  <a href="property-details.html" class="mini-btn">Details</a>
                </div>
              </div>
            </article><!-- End Mini Card -->

            <article class="mini-card" data-aos="fade-up" data-aos-delay="300">
              <a href="property-details.html" class="thumb">
                <img src="{{ Vite::asset('resources/assets/guest/img/real-estate/property-interior-7.webp') }}" alt="Penthouse" class="img-fluid" loading="lazy">
                <span class="label featured"><i class="bi bi-gem"></i> Featured</span>
              </a>
              <div class="mini-body">
                <h4><a href="property-details.html">Glass-Roof Penthouse Suite</a></h4>
                <div class="mini-loc"><i class="bi bi-geo"></i> Miami, FL 33131</div>
                <div class="mini-specs">
                  <span><i class="bi bi-door-open"></i> 3</span>
                  <span><i class="bi bi-droplet"></i> 3</span>
                  <span><i class="bi bi-rulers"></i> 2,120 sq ft</span>
                </div>
                <div class="mini-foot">
                  <div class="mini-price">$1,290,000</div>
                  <a href="property-details.html" class="mini-btn">Details</a>
                </div>
              </div>
            </article><!-- End Mini Card -->

          </div><!-- End Mini List -->

        </div>

        <div class="row gy-4 mt-4">

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <article class="stack-card">
              <figure class="stack-media">
                <img src="{{ Vite::asset('resources/assets/guest/img/real-estate/property-exterior-8.webp') }}" alt="Modern Facade" class="img-fluid" loading="lazy">
                <figcaption>
                  <span class="chip exclusive">Exclusive</span>
                </figcaption>
              </figure>
              <div class="stack-body">
                <h5><a href="property-details.html">Modern Courtyard Residence</a></h5>
                <div class="stack-loc"><i class="bi bi-geo-alt"></i> Scottsdale, AZ 85251</div>
                <ul class="stack-specs">
                  <li><i class="bi bi-door-open"></i> 4</li>
                  <li><i class="bi bi-droplet"></i> 3</li>
                  <li><i class="bi bi-aspect-ratio"></i> 2,980 sq ft</li>
                </ul>
                <div class="stack-foot">
                  <span class="stack-price">$1,025,000</span>
                  <a href="property-details.html" class="stack-link">View</a>
                </div>
              </div>
            </article>
          </div>

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="350">
            <article class="stack-card">
              <figure class="stack-media">
                <img src="{{ Vite::asset('resources/assets/guest/img/real-estate/property-interior-10.webp') }}" alt="Cozy Interior" class="img-fluid" loading="lazy">
                <figcaption>
                  <span class="chip hot">Hot</span>
                </figcaption>
              </figure>
              <div class="stack-body">
                <h5><a href="property-details.html">Cozy Lakeview Townhouse</a></h5>
                <div class="stack-loc"><i class="bi bi-geo-alt"></i> Madison, WI 53703</div>
                <ul class="stack-specs">
                  <li><i class="bi bi-door-open"></i> 3</li>
                  <li><i class="bi bi-droplet"></i> 2</li>
                  <li><i class="bi bi-aspect-ratio"></i> 1,780 sq ft</li>
                </ul>
                <div class="stack-foot">
                  <span class="stack-price">$429,000</span>
                  <a href="property-details.html" class="stack-link">View</a>
                </div>
              </div>
            </article>
          </div>

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="400">
            <article class="stack-card">
              <figure class="stack-media">
                <img src="{{ Vite::asset('resources/assets/guest/img/real-estate/property-exterior-10.webp') }}" alt="Garden Home" class="img-fluid" loading="lazy">
                <figcaption>
                  <span class="chip new">New</span>
                </figcaption>
              </figure>
              <div class="stack-body">
                <h5><a href="property-details.html">Garden Home Near Downtown</a></h5>
                <div class="stack-loc"><i class="bi bi-geo-alt"></i> Raleigh, NC 27601</div>
                <ul class="stack-specs">
                  <li><i class="bi bi-door-open"></i> 3</li>
                  <li><i class="bi bi-droplet"></i> 2</li>
                  <li><i class="bi bi-aspect-ratio"></i> 1,920 sq ft</li>
                </ul>
                <div class="stack-foot">
                  <span class="stack-price">$512,000</span>
                  <a href="property-details.html" class="stack-link">View</a>
                </div>
              </div>
            </article>
          </div>

        </div>

      </div>

    </section><!-- /Featured Properties Section -->


    <!-- Featured Agents Section -->
    <section id="featured-agents" class="featured-agents section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Featured Agents</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-5 justify-content-center">

          <div class="col-lg-6 col-xl-4" data-aos="fade-up" data-aos-delay="100">
            <div class="agent-card">
              <div class="agent-image">
                <img src="{{ Vite::asset('resources/assets/guest/img/real-estate/agent-5.webp') }}" alt="Top Agent" class="img-fluid">
                <div class="agent-overlay">
                  <div class="contact-buttons">
                    <a href="tel:+14159876543" class="btn-contact" title="Call Agent">
                      <i class="bi bi-telephone"></i>
                    </a>
                    <a href="mailto:lisa.thompson@example.com" class="btn-contact" title="Email Agent">
                      <i class="bi bi-envelope"></i>
                    </a>
                    <a href="#" class="btn-contact" title="WhatsApp">
                      <i class="bi bi-whatsapp"></i>
                    </a>
                  </div>
                </div>
                <div class="status-badge top-agent">Top Agent</div>
              </div>
              <div class="agent-info">
                <div class="agent-meta">
                  <h3 class="agent-name">Lisa Thompson</h3>
                  <p class="agent-title">Luxury Property Expert</p>
                </div>
                <div class="agent-stats">
                  <div class="stat-item">
                    <span class="stat-number">150+</span>
                    <span class="stat-label">Properties Sold</span>
                  </div>
                  <div class="stat-divider"></div>
                  <div class="stat-item">
                    <span class="stat-number">4.9</span>
                    <span class="stat-label">Rating</span>
                  </div>
                </div>
                <div class="location-tag">
                  <i class="bi bi-geo-alt"></i>
                  <span>Miami Beach</span>
                </div>
                <div class="specialties">
                  <span class="specialty-tag">Waterfront</span>
                  <span class="specialty-tag">High-Rise</span>
                </div>
                <a href="agent-profile.html" class="profile-link">View Full Profile</a>
              </div>
            </div>
          </div><!-- End Agent Card -->

          <div class="col-lg-6 col-xl-4" data-aos="fade-up" data-aos-delay="200">
            <div class="agent-card">
              <div class="agent-image">
                <img src="{{ Vite::asset('resources/assets/guest/img/real-estate/agent-4.webp') }}" alt="Top Agent" class="img-fluid">
                <div class="agent-overlay">
                  <div class="contact-buttons">
                    <a href="tel:+14159876544" class="btn-contact" title="Call Agent">
                      <i class="bi bi-telephone"></i>
                    </a>
                    <a href="mailto:robert.chen@example.com" class="btn-contact" title="Email Agent">
                      <i class="bi bi-envelope"></i>
                    </a>
                    <a href="#" class="btn-contact" title="WhatsApp">
                      <i class="bi bi-whatsapp"></i>
                    </a>
                  </div>
                </div>
                <div class="status-badge certified">Certified</div>
              </div>
              <div class="agent-info">
                <div class="agent-meta">
                  <h3 class="agent-name">Robert Chen</h3>
                  <p class="agent-title">Commercial Specialist</p>
                </div>
                <div class="agent-stats">
                  <div class="stat-item">
                    <span class="stat-number">90+</span>
                    <span class="stat-label">Commercial Sales</span>
                  </div>
                  <div class="stat-divider"></div>
                  <div class="stat-item">
                    <span class="stat-number">4.8</span>
                    <span class="stat-label">Rating</span>
                  </div>
                </div>
                <div class="location-tag">
                  <i class="bi bi-geo-alt"></i>
                  <span>Downtown</span>
                </div>
                <div class="specialties">
                  <span class="specialty-tag">Office Space</span>
                  <span class="specialty-tag">Retail</span>
                </div>
                <a href="agent-profile.html" class="profile-link">View Full Profile</a>
              </div>
            </div>
          </div><!-- End Agent Card -->

          <div class="col-lg-6 col-xl-4" data-aos="fade-up" data-aos-delay="300">
            <div class="agent-card">
              <div class="agent-image">
                <img src="{{ Vite::asset('resources/assets/guest/img/real-estate/agent-8.webp') }}" alt="Top Agent" class="img-fluid">
                <div class="agent-overlay">
                  <div class="contact-buttons">
                    <a href="tel:+14159876545" class="btn-contact" title="Call Agent">
                      <i class="bi bi-telephone"></i>
                    </a>
                    <a href="mailto:maria.gonzalez@example.com" class="btn-contact" title="Email Agent">
                      <i class="bi bi-envelope"></i>
                    </a>
                    <a href="#" class="btn-contact" title="WhatsApp">
                      <i class="bi bi-whatsapp"></i>
                    </a>
                  </div>
                </div>
                <div class="status-badge new-star">Rising Star</div>
              </div>
              <div class="agent-info">
                <div class="agent-meta">
                  <h3 class="agent-name">Maria Gonzalez</h3>
                  <p class="agent-title">Residential Advisor</p>
                </div>
                <div class="agent-stats">
                  <div class="stat-item">
                    <span class="stat-number">75+</span>
                    <span class="stat-label">Happy Families</span>
                  </div>
                  <div class="stat-divider"></div>
                  <div class="stat-item">
                    <span class="stat-number">4.9</span>
                    <span class="stat-label">Rating</span>
                  </div>
                </div>
                <div class="location-tag">
                  <i class="bi bi-geo-alt"></i>
                  <span>Suburbs</span>
                </div>
                <div class="specialties">
                  <span class="specialty-tag">Family Homes</span>
                  <span class="specialty-tag">First-Time</span>
                </div>
                <a href="agent-profile.html" class="profile-link">View Full Profile</a>
              </div>
            </div>
          </div><!-- End Agent Card -->

        </div>

        <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="400">
          <a href="agents.html" class="explore-agents-btn">
            <span>Explore All Our Agents</span>
            <i class="bi bi-arrow-right-circle"></i>
          </a>
        </div>

      </div>

    </section><!-- /Featured Agents Section -->

    <!-- Why Us Section -->
    <section id="why-us" class="why-us section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Why Us</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center gy-5">

          <div class="col-lg-5" data-aos="fade-right" data-aos-delay="200">
            <div class="image-showcase">
              <div class="main-image-wrapper">
                <img src="{{ Vite::asset('resources/assets/guest/img/real-estate/property-exterior-3.webp') }}" alt="Premium Property" class="img-fluid main-image">
                <div class="image-overlay">
                  <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox">
                    <div class="overlay-content">
                      <i class="bi bi-play-circle-fill play-icon"></i>
                      <span>Watch Our Story</span>
                    </div>
                  </a>
                </div>
              </div>

              <div class="floating-stats">
                <div class="stat-badge">
                  <span class="stat-number">15+</span>
                  <span class="stat-text">Years Excellence</span>
                </div>
                <div class="stat-badge">
                  <span class="stat-number">3.2K</span>
                  <span class="stat-text">Happy Clients</span>
                </div>
              </div>

              <div class="experience-card">
                <div class="card-icon">
                  <i class="bi bi-gem"></i>
                </div>
                <div class="card-content">
                  <h5>Premier Service</h5>
                  <p>Luxury real estate expertise since 2009</p>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-7" data-aos="fade-left" data-aos-delay="300">
            <div class="content-wrapper">
              <div class="section-badge">
                <i class="bi bi-star-fill me-2"></i>
                Why Elite Properties
              </div>

              <h2>Your Gateway to Exceptional Real Estate Experiences</h2>
              <p class="lead-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>

              <div class="benefits-grid">
                <div class="benefit-item" data-aos="fade-up" data-aos-delay="400">
                  <div class="benefit-icon">
                    <i class="bi bi-geo-alt-fill"></i>
                  </div>
                  <div class="benefit-content">
                    <h4>Prime Locations</h4>
                    <p>Exclusive access to the most sought-after neighborhoods and emerging markets.</p>
                  </div>
                </div>

                <div class="benefit-item" data-aos="fade-up" data-aos-delay="450">
                  <div class="benefit-icon">
                    <i class="bi bi-shield-fill-check"></i>
                  </div>
                  <div class="benefit-content">
                    <h4>Guaranteed Results</h4>
                    <p>Our proven track record ensures successful transactions and satisfied clients.</p>
                  </div>
                </div>

                <div class="benefit-item" data-aos="fade-up" data-aos-delay="500">
                  <div class="benefit-icon">
                    <i class="bi bi-clock-fill"></i>
                  </div>
                  <div class="benefit-content">
                    <h4>Fast Processing</h4>
                    <p>Streamlined processes and expert negotiation to close deals efficiently.</p>
                  </div>
                </div>

                <div class="benefit-item" data-aos="fade-up" data-aos-delay="550">
                  <div class="benefit-icon">
                    <i class="bi bi-people-fill"></i>
                  </div>
                  <div class="benefit-content">
                    <h4>Expert Team</h4>
                    <p>Certified professionals with deep market knowledge and client dedication.</p>
                  </div>
                </div>
              </div>

              <div class="achievement-highlights" data-aos="fade-up" data-aos-delay="600">
                <div class="highlight-item">
                  <span class="highlight-number purecounter" data-purecounter-start="0" data-purecounter-end="94" data-purecounter-duration="2"></span>%
                  <span class="highlight-label">Success Rate</span>
                </div>
                <div class="highlight-divider"></div>
                <div class="highlight-item">
                  <span class="highlight-number purecounter" data-purecounter-start="0" data-purecounter-end="1800" data-purecounter-duration="2"></span>+
                  <span class="highlight-label">Properties Sold</span>
                </div>
                <div class="highlight-divider"></div>
                <div class="highlight-item">
                  <span class="highlight-number purecounter" data-purecounter-start="0" data-purecounter-end="24" data-purecounter-duration="2"></span>/7
                  <span class="highlight-label">Support Available</span>
                </div>
              </div>

              <div class="action-buttons" data-aos="fade-up" data-aos-delay="650">
                <a href="properties.html" class="btn btn-primary">Explore Properties</a>
                <a href="contact.html" class="btn btn-outline">Schedule Consultation</a>
              </div>
            </div>
          </div>

        </div>

      </div>

    </section><!-- /Why Us Section -->

    <!-- Recent Blog Posts Section -->
    <section id="recent-blog-posts" class="recent-blog-posts section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Recent Blog Posts</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-5">

          <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="post-item position-relative h-100">

              <div class="post-img position-relative overflow-hidden">
                <img src="{{ Vite::asset('resources/assets/guest/img/blog/blog-post-1.webp') }}" class="img-fluid" alt="">
                <span class="post-date">December 12</span>
              </div>

              <div class="post-content d-flex flex-column">
                <h3 class="post-title">Eum ad dolor et. Autem aut fugiat debitis</h3>
                <a href="blog-details.html" class="readmore stretched-link"><span>Read More</span><i class="bi bi-arrow-right"></i></a>
              </div>

            </div>
          </div><!-- End post item -->

          <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="post-item position-relative h-100">

              <div class="post-img position-relative overflow-hidden">
                <img src="{{ Vite::asset('resources/assets/guest/img/blog/blog-post-2.webp') }}" class="img-fluid" alt="">
                <span class="post-date">July 17</span>
              </div>

              <div class="post-content d-flex flex-column">
                <h3 class="post-title">Et repellendus molestiae qui est sed omnis</h3>
                <a href="blog-details.html" class="readmore stretched-link"><span>Read More</span><i class="bi bi-arrow-right"></i></a>
              </div>

            </div>
          </div><!-- End post item -->

          <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="post-item position-relative h-100">

              <div class="post-img position-relative overflow-hidden">
                <img src="{{ Vite::asset('resources/assets/guest/img/blog/blog-post-3.webp') }}" class="img-fluid" alt="">
                <span class="post-date">September 05</span>
              </div>

              <div class="post-content d-flex flex-column">
                <h3 class="post-title">Quia assumenda est et veritati tirana ploder</h3>
                <a href="blog-details.html" class="readmore stretched-link"><span>Read More</span><i class="bi bi-arrow-right"></i></a>
              </div>

            </div>
          </div><!-- End post item -->

        </div>

      </div>

    </section><!-- /Recent Blog Posts Section -->

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
                    <img src="{{ $news->cover_image['url'] ?? '' }}"
                         class="img-fluid rounded"
                         alt="{{ e($news->judul) }}"
                         style="width: 80px; height: 80px; object-fit: cover;"
                         onerror="this.onerror=null; this.src='{{ Vite::asset('resources/assets/guest/img/person/person-m-10.webp') }}';">
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
                  <img src="{{ Vite::asset('resources/assets/guest/img/real-estate/property-exterior-3.webp') }}" class="img-fluid" alt="{{ e($request->nama_software) }}">
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
    <section class="call-to-action-2 call-to-action section light-background" id="call-to-action">
      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center">
          <div class="col-lg-6 order-2 order-lg-1" data-aos="fade-right" data-aos-delay="200">

            <div class="cta-content">
              <div class="section-badge">Your Property Journey Starts Here</div>
              <h2>Ready to Find Your Perfect Investment?</h2>
              <p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Mauris viverra veniam sit amet lacus cursus. Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>

              <div class="benefits-list">
                <div class="benefit-item" data-aos="fade-up" data-aos-delay="300">
                  <i class="bi bi-check-circle-fill"></i>
                  <span>Expert market analysis and insights</span>
                </div>
                <div class="benefit-item" data-aos="fade-up" data-aos-delay="350">
                  <i class="bi bi-check-circle-fill"></i>
                  <span>Personalized property recommendations</span>
                </div>
                <div class="benefit-item" data-aos="fade-up" data-aos-delay="400">
                  <i class="bi bi-check-circle-fill"></i>
                  <span>End-to-end transaction support</span>
                </div>
              </div>

              <div class="cta-actions">
                <a href="contact.html" class="btn btn-primary">
                  <i class="bi bi-person-lines-fill"></i>
                  Get Free Consultation
                </a>
                <a href="contact.html" class="btn btn-secondary">
                  <i class="bi bi-telephone-fill"></i>
                  Call (555) 123-4567
                </a>
              </div>

            </div><!-- End CTA Content -->

          </div><!-- End Left Column -->

          <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-left" data-aos-delay="250">

            <div class="cta-visual">
              <div class="main-image">
                <img src="{{ Vite::asset('resources/assets/guest/img/real-estate/property-exterior-5.webp') }}" alt="Property Investment" class="img-fluid">
                <div class="overlay-badge">
                  <i class="bi bi-star-fill"></i>
                  <span>Trusted by 500+ Clients</span>
                </div>
              </div>

              <div class="floating-stats">
                <div class="stat-card" data-aos="zoom-in" data-aos-delay="450">
                  <div class="stat-icon">
                    <i class="bi bi-house-heart-fill"></i>
                  </div>
                  <div class="stat-info">
                    <span class="stat-number">850+</span>
                    <span class="stat-label">Properties Sold</span>
                  </div>
                </div>

                <div class="stat-card" data-aos="zoom-in" data-aos-delay="500">
                  <div class="stat-icon">
                    <i class="bi bi-trophy-fill"></i>
                  </div>
                  <div class="stat-info">
                    <span class="stat-number">15</span>
                    <span class="stat-label">Years Experience</span>
                  </div>
                </div>
              </div>

            </div><!-- End CTA Visual -->

          </div><!-- End Right Column -->
        </div>

      </div>
    </section><!-- /Call To Action Section -->

@endsection

