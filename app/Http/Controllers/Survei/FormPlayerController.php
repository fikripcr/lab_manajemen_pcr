<?php
namespace App\Http\Controllers\Survei;

use App\Http\Controllers\Controller;
use App\Models\Survei\Jawaban;
use App\Models\Survei\Pengisian;
use App\Models\Survei\Survei;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormPlayerController extends Controller
{
    public function show($slug)
    {
        $survei = Survei::where('slug', $slug)
            ->where('is_aktif', true)
            ->firstOrFail();

        // Check date range
        if ($survei->tanggal_mulai && now()->lt($survei->tanggal_mulai)) {
            abort(403, 'Survei belum dimulai.');
        }
        if ($survei->tanggal_selesai && now()->gt($survei->tanggal_selesai)) {
            abort(403, 'Survei sudah berakhir.');
        }

        // Check login requirement
        if ($survei->wajib_login && ! auth()->check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengisi survei ini.');
        }

        // Check if user already filled (if bisa_isi_ulang is false)
        if (! $survei->bisa_isi_ulang && auth()->check()) {
            $hasFilled = Pengisian::where('survei_id', $survei->id)
                ->where('user_id', auth()->id())
                ->exists();
            if ($hasFilled) {
                return redirect()->route('dashboard')->with('error', 'Anda sudah mengisi survei ini.');
            }
        }

        // Load Structure (ordered)
        $survei->load(['halaman' => fn($q) => $q->orderBy('urutan'), 'halaman.pertanyaan' => fn($q) => $q->orderBy('urutan'), 'halaman.pertanyaan.opsi']);

        return view('pages.survei.player.show', compact('survei'));
    }

    public function store(Request $request, $slug)
    {
        $survei = Survei::where('slug', $slug)
            ->where('is_aktif', true)
            ->firstOrFail();

        // Date range validation
        if ($survei->tanggal_selesai && now()->gt($survei->tanggal_selesai)) {
            return back()->with('error', 'Survei sudah berakhir, jawaban tidak dapat dikirim.');
        }

        // Build dynamic validation rules
        $rules         = ['jawaban' => 'required|array'];
        $pertanyaanMap = $survei->pertanyaan()->pluck('tipe', 'id');

        foreach ($pertanyaanMap as $id => $tipe) {
            $pertanyaan = $survei->pertanyaan()->find($id);
            if ($pertanyaan && $pertanyaan->wajib_diisi) {
                $rules["jawaban.{$id}"] = 'required';
            }
        }

        $request->validate($rules, [
            'jawaban.required'   => 'Anda harus mengisi minimal satu jawaban.',
            'jawaban.*.required' => 'Pertanyaan wajib harus diisi.',
        ]);

        DB::beginTransaction();
        try {
            // Create Header
            $pengisian = Pengisian::create([
                'survei_id'     => $survei->id,
                'user_id'       => auth()->id(),
                'status'        => 'Selesai',
                'waktu_mulai'   => now(),
                'waktu_selesai' => now(),
                'ip_address'    => $request->ip(),
            ]);

            // Pre-load valid pertanyaan IDs and their types for fast lookup
            $validPertanyaan = $survei->pertanyaan()->pluck('tipe', 'id');

            // Batch prepare answers
            $answerRows = [];
            $now        = now();

            if ($request->has('jawaban')) {
                foreach ($request->jawaban as $pertanyaanId => $nilai) {
                    if (! $validPertanyaan->has($pertanyaanId)) {
                        continue;
                    }

                    $tipe = $validPertanyaan[$pertanyaanId];

                    $row = [
                        'pengisian_id'  => $pengisian->id,
                        'pertanyaan_id' => $pertanyaanId,
                        'opsi_id'       => null,
                        'nilai_teks'    => null,
                        'nilai_angka'   => null,
                        'nilai_json'    => null,
                        'nilai_tanggal' => null,
                        'dibuat_pada'   => $now,
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ];

                    // Map Input Type to Column
                    if (is_array($nilai)) {
                        $row['nilai_json'] = json_encode($nilai);
                    } elseif (in_array($tipe, ['Angka', 'Skala_Linear', 'Rating_Bintang'])) {
                        $row['nilai_angka'] = (int) $nilai;
                    } elseif ($tipe == 'Tanggal') {
                        $row['nilai_tanggal'] = $nilai;
                    } elseif (in_array($tipe, ['Pilihan_Ganda', 'Dropdown'])) {
                        if (is_numeric($nilai)) {
                            $row['opsi_id'] = $nilai;
                        } else {
                            $row['nilai_teks'] = $nilai;
                        }
                    } else {
                        $row['nilai_teks'] = $nilai;
                    }

                    $answerRows[] = $row;
                }
            }

            // Batch insert all answers in one query
            if (! empty($answerRows)) {
                Jawaban::insert($answerRows);
            }

            DB::commit();

            return redirect()->route('survei.public.thankyou', $survei->slug);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan jawaban: ' . $e->getMessage());
        }
    }

    public function thankyou($slug)
    {
        $survei = Survei::where('slug', $slug)->firstOrFail();
        return view('pages.survei.player.thankyou', compact('survei'));
    }
}
