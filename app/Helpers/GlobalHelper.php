<?php

use Carbon\Carbon;

if (! function_exists('formatTanggalIndo')) {
    /**
     * Format tanggal ke bahasa Indonesia
     *
     * @param  mixed  $tanggal
     * @return string
     */
    function formatTanggalIndo($tanggal)
    {
        if (! $tanggal) {
            return '-';
        }

        // Cek jika ini adalah format waktu murni (HH:ii tanpa tanggal)
        if (is_string($tanggal) && preg_match('/^\d{2}:\d{2}$/', $tanggal)) {
            return $tanggal;
        }

        // Parse date using Carbon
        $date = Carbon::parse($tanggal);

        // Jika hanya waktu (tanpa tanggal valid 0000-00-00), tampilkan sebagai waktu saja
        if ($date->year < 1) { // This condition checks if the year is effectively zero or invalid, indicating a time-only value
            return $date->format('H:i');
        }

        // Jika hanya tanggal (00:00:00), tampilkan sebagai tanggal saja
        if ($date->format('H:i:s') === '00:00:00') {
            return $date->translatedFormat('d F Y');
        }

        // Tampilkan tanggal dan waktu lengkap (Hari, dd Bulan YYYY HH:ii)
        return $date->translatedFormat('l, d F Y H:i');
    }
}

if (! function_exists('formatTanggalWaktuIndo')) {
    /**
     * @deprecated Use formatTanggalIndo() instead - this is kept for backward compatibility
     *
     * @param  mixed  $tanggal
     * @return string
     */
    function formatTanggalWaktuIndo($tanggal)
    {
        return formatTanggalIndo($tanggal);
    }
}

if (! function_exists('formatWaktuSaja')) {
    /**
     * Format hanya waktu ke format HH:ii (tanpa tanggal)
     *
     * @param  mixed  $waktu
     * @return string
     */
    function formatWaktuSaja($waktu)
    {
        if (! $waktu) {
            return '-';
        }

        // Cek jika ini adalah format waktu murni (HH:ii tanpa tanggal)
        if (is_string($waktu) && preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $waktu)) {
            $parts = explode(':', $waktu);

            return $parts[0].':'.$parts[1]; // Return HH:ii
        }

        // Parse date using Carbon
        $date = Carbon::parse($waktu);

        // Kembalikan hanya format jam:menit
        return $date->format('H:i');
    }
}

if (! function_exists('generateKodeInventaris')) {
    /**
     * Generate unique kode inventaris
     *
     * @param  int  $labId
     * @param  int  $inventarisId
     * @return string
     */
    function generateKodeInventaris($labId, $inventarisId)
    {
        $lab = \App\Models\Lab\Lab::find($labId);
        $inventaris = \App\Models\Lab\Inventaris::find($inventarisId);

        if (! $lab || ! $inventaris) {
            return null;
        }

        $labCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $lab->name ?? ''), 0, 3));
        $invCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $inventaris->nama_alat ?? ''), 0, 3));
        $prefix = sprintf('%s-%s', $labCode, $invCode);

        // Ambil jumlah inventaris yang sudah ada di lab ini dengan prefix yang sama untuk urutan
        $count = \App\Models\Lab\LabInventaris::where('lab_id', $labId)
            ->where('kode_inventaris', 'like', $prefix.'-%')
            ->count() + 1;

        return sprintf('%s-%04d', $prefix, $count);
    }
}

if (! function_exists('jsonResponse')) {
    /**
     * Create standardized JSON response
     *
     * @param  bool  $success
     * @param  string  $message
     * @param  array  $data
     * @param  int  $code
     * @param  string|null  $redirect
     * @return \Illuminate\Http\JsonResponse
     */
    function jsonResponse($success = true, $message = '', $data = [], $code = 200, $redirect = null)
    {
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        if (! empty($data)) {
            $response['data'] = $data;
        }

        if ($redirect) {
            $response['redirect'] = $redirect;
        }

        return response()->json($response, $code);
    }
}

if (! function_exists('jsonSuccess')) {
    /**
     * Create standardized success JSON response
     *
     * Usage:
     *   jsonSuccess('Message')
     *   jsonSuccess('Message', '/redirect-url')
     *   jsonSuccess('Message', '/redirect-url', ['data' => $data])
     *   jsonSuccess(['data' => $data, 'message' => 'Custom']) // Array mode
     *
     * @param  mixed  $arg1
     * @param  mixed  $arg2
     * @param  mixed  $arg3
     * @param  int  $arg4
     * @return \Illuminate\Http\JsonResponse
     */
    function jsonSuccess($arg1 = 'Success', $arg2 = null, $arg3 = [], $arg4 = 200)
    {
        // Array mode: jsonSuccess(['data' => ..., 'message' => ...])
        if (is_array($arg1)) {
            return jsonResponse(
                true,
                $arg1['message'] ?? 'Success',
                $arg1['data'] ?? $arg1,
                $arg1['code'] ?? 200,
                $arg1['redirect'] ?? null
            );
        }

        // Standard mode: jsonSuccess('message', 'redirect', $data, $code)
        return jsonResponse(true, $arg1, $arg3, $arg4, $arg2);
    }
}

if (! function_exists('jsonError')) {
    /**
     * Create standardized error JSON response
     *
     * @param  string  $message
     * @param  int  $code
     * @param  array  $data
     * @param  string|null  $redirect
     * @return \Illuminate\Http\JsonResponse
     */
    function jsonError($message = 'Error', $code = 500, $data = [], $redirect = null)
    {
        return jsonResponse(false, $message, $data, $code, $redirect);
    }
}
if (! function_exists('pemutu_can_modify')) {
    /**
     * Check if a document/indicator can be modified based on the current period's status.
     *
     * @param  int  $year
     * @param  string|null  $kelompok
     * @return bool
     */
    function pemutu_can_modify($year, ?string $kelompok = null): bool
    {
        $year = (int) $year;
        if (! $year) {
            return false;
        }
        return app(\App\Services\Pemutu\PeriodeSpmiService::class)->isPenetapanOpen($year, $kelompok);
    }
}
