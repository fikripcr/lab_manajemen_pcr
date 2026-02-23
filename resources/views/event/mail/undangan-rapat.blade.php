{{-- Email Undangan Rapat --}}
@component('mail::message')

# Undangan Rapat

**{{ $rapat->judul_kegiatan }}**

Berikut adalah detail undangan rapat untuk Anda:

@component('mail::panel')
- **Jenis Rapat:** {{ $rapat->jenis_rapat }}
- **Tanggal:** {{ $rapat->tgl_rapat->format('d F Y') }}
- **Waktu:** {{ $rapat->waktu_mulai->format('H:i') }} - {{ $rapat->waktu_selesai->format('H:i') }}
- **Durasi:** {{ $rapat->waktu_mulai->diffInMinutes($rapat->waktu_selesai) }} menit
- **Tempat:** {{ $rapat->tempat_rapat }}
- **Jabatan dalam Rapat:** {{ $jabatan }}
@endcomponent

@if($rapat->keterangan)
## Keterangan Tambahan

{{ $rapat->keterangan }}
@endif

@if($rapat->agendas->count() > 0)
## Agenda Rapat

@foreach($rapat->agendas as $index => $agenda)
{{ $loop->iteration }}. {{ $agenda->judul_agenda }}
@endforeach
@endif

Silakan klik tombol di bawah ini untuk melihat detail lengkap rapat:

@component('mail::button', ['url' => route('Kegiatan.rapat.show', $rapat->encrypted_rapat_id)])
Lihat Detail Rapat
@endcomponent

**Catatan:**
- Harap hadir tepat waktu
- Jika berhalangan hadir, mohon informasikan kepada ketua rapat
- Siapkan materi yang diperlukan sesuai agenda

@endcomponent
