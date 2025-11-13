<!-- Vendor JS Files -->
<script src="{{ asset('assets-admin') }}/vendor/libs/jquery/jquery.js"></script>
  <script src="{{asset('assets-guest')}}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="{{asset('assets-guest')}}/vendor/php-email-form/validate.js"></script>
  <script src="{{asset('assets-guest')}}/vendor/aos/aos.js"></script>
  <script src="{{asset('assets-guest')}}/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="{{asset('assets-guest')}}/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="{{asset('assets-guest')}}/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="{{asset('assets-guest')}}/vendor/drift-zoom/Drift.min.js"></script>

  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

  <!-- Main JS File -->
  <script src="{{asset('assets-guest')}}/js/main.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


  <!-- SweetAlert Component JS -->
  <script>
    // SweetAlert function for success messages
    function showSuccessMessage(title, text = '') {
        Swal.fire({
            icon: 'success',
            title: title,
            text: text,
            showConfirmButton: false,
            timer: 1500
        });
    }

    // SweetAlert function for error messages
    function showErrorMessage(title, text = '') {
        Swal.fire({
            icon: 'error',
            title: title,
            text: text
        });
    }

    // SweetAlert function for info messages
    function showInfoMessage(title, text = '') {
        Swal.fire({
            icon: 'info',
            title: title,
            text: text
        });
    }

    // SweetAlert function for warning messages
    function showWarningMessage(title, text = '') {
        Swal.fire({
            icon: 'warning',
            title: title,
            text: text
        });
    }
  </script>
