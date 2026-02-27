<x-tabler.form-modal
    title="Beri Penilaian Layanan"
    route="{{ route('eoffice.feedback.store') }}"
    method="POST"
    submitText="Kirim Feedback"
    submitIcon="ti-star"
>
    <input type="hidden" name="layanan_id" value="{{ $layanan->encrypted_layanan_id }}">
    
    <div class="text-center py-4">
        <div class="mb-3">
            <div class="text-muted mb-2">Ketuk bintang untuk memberi nilai</div>
            <div class="rating-stars">
                @for($i=1; $i<=5; $i++)
                    <input type="radio" name="rating" id="rating-{{ $i }}" value="{{ $i }}" class="d-none">
                    <label for="rating-{{ $i }}" class="cursor-pointer">
                        <i class="ti ti-star fs-1 text-muted star-icon" data-value="{{ $i }}"></i>
                    </label>
                @endfor
            </div>
            <div class="mt-2 fw-bold text-warning" id="rating-text">Pilih rating...</div>
        </div>
        <div class="mb-3 text-start">
            <x-tabler.form-textarea name="catatan" label="Catatan / Masukan (Opsional)" rows="3" placeholder="Ceritakan pengalaman Anda..." />
        </div>
    </div>

    <script>
        (function() {
            const stars = document.querySelectorAll('.star-icon');
            const ratingText = document.getElementById('rating-text');
            const ratingLabels = ['Sangat Buruk', 'Buruk', 'Cukup', 'Baik', 'Sangat Baik'];

            function highlightStars(count) {
                stars.forEach(s => {
                    if (s.dataset.value <= count) {
                        s.classList.remove('text-muted');
                        s.classList.add('text-yellow');
                        s.classList.remove('ti-star');
                        s.classList.add('ti-star-filled');
                    } else {
                        s.classList.add('text-muted');
                        s.classList.remove('text-yellow');
                        s.classList.add('ti-star');
                        s.classList.remove('ti-star-filled');
                    }
                });
            }

            stars.forEach(star => {
                star.addEventListener('mouseover', function() {
                    highlightStars(this.dataset.value);
                });

                star.addEventListener('mouseout', function() {
                    let checked = document.querySelector('input[name="rating"]:checked');
                    if (checked) {
                        highlightStars(checked.value);
                    } else {
                        stars.forEach(s => {
                            s.classList.add('text-muted');
                            s.classList.remove('text-yellow');
                            s.classList.add('ti-star');
                            s.classList.remove('ti-star-filled');
                        });
                    }
                });

                star.addEventListener('click', function() {
                    let val = this.dataset.value;
                    document.getElementById('rating-' + val).checked = true;
                    highlightStars(val);
                    ratingText.textContent = ratingLabels[val - 1];
                });
            });
        })();
    </script>
</x-tabler.form-modal>
