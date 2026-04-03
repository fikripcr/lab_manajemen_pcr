@props(['activeKelompok' => null, 'redirect' => null])

<div {{ $attributes->merge(['class' => 'btn-group p-1 bg-light rounded-pill shadow-sm', 'style' => 'border: 1px solid #e6e8e9;']) }}>
    <a href="{{ route('pemutu.set-kelompok', ['kelompok' => 'akademik', 'redirect' => $redirect]) }}" 
       class="btn {{ $activeKelompok === 'akademik' ? 'btn-white shadow-sm fw-bold border-0 active text-primary' : 'btn-ghost-secondary border-0 opacity-75' }} rounded-pill px-4 transition-all duration-200">
        <i class="ti ti-school me-2"></i>Akademik
    </a>
    <a href="{{ route('pemutu.set-kelompok', ['kelompok' => 'non_akademik', 'redirect' => $redirect]) }}" 
       class="btn {{ $activeKelompok === 'non_akademik' ? 'btn-white shadow-sm fw-bold border-0 active text-primary' : 'btn-ghost-secondary border-0 opacity-75' }} rounded-pill px-4 transition-all duration-200">
        <i class="ti ti-building-community me-2"></i>Non Akademik
    </a>
</div>
