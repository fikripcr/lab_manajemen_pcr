<?php

use Carbon\Carbon;

if (! function_exists('formatTanggalIndo')) {
    /**
     * Format tanggal ke bahasa Indonesia
     *
     * @param mixed $tanggal
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

if (! function_exists('formatWaktuSaja')) {
    /**
     * Format hanya waktu ke format HH:ii (tanpa tanggal)
     *
     * @param mixed $waktu
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
            return $parts[0] . ':' . $parts[1]; // Return HH:ii
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
     * @param int $labId
     * @param int $inventarisId
     * @return string
     */
    function generateKodeInventaris($labId, $inventarisId)
    {
        $lab        = \App\Models\Lab::find($labId);
        $inventaris = \App\Models\Inventaris::find($inventarisId);

        if (! $lab || ! $inventaris) {
            return null;
        }

        $labCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $lab->name ?? ''), 0, 3));
        $invCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $inventaris->nama_alat ?? ''), 0, 3));

        // Ambil jumlah inventaris yang sudah ada di lab ini untuk urutan
        $count = \App\Models\LabInventaris::where('lab_id', $labId)
            ->where('inventaris_id', $inventarisId)
            ->count() + 1;

        return sprintf('%s-%s-%04d', $labCode, $invCode, $count);
    }
}

if (! function_exists('jsonResponse')) {
    /**
     * Create standardized JSON response
     *
     * @param bool $success
     * @param string $message
     * @param array $data
     * @param int $code
     * @param string|null $redirect
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
     * Handles two modes:
     * 1. Smart Array: jsonSuccess(['data' => ..., 'redirect' => ...])
     * 2. Legacy: jsonSuccess('Message', '/url', ['data'])
     *
     * @param mixed $arg1
     * @param mixed $arg2
     * @param mixed $arg3
     * @param int $arg4
     * @return \Illuminate\Http\JsonResponse
     */
    function jsonSuccess($arg1 = 'Success', $arg2 = null, $arg3 = [], $arg4 = 200)
    {
        // MODE 1: Smart Array Input
        if (is_array($arg1)) {
            $params   = $arg1;
            $reserved = ['message', 'data', 'redirect', 'code'];

            // Check if array contains any reserved control keys
            $hasControlKeys = ! empty(array_intersect_key($params, array_flip($reserved)));

            if ($hasControlKeys) {
                // It is a Config Array
                return jsonResponse(
                    true,
                    $params['message'] ?? 'Success',
                    $params['data'] ?? [],
                    $params['code'] ?? 200,
                    $params['redirect'] ?? null
                );
            }

            // It is just a Raw Data Array
            return jsonResponse(true, 'Success', $params);
        }

        // MODE 2: Legacy / Standard Input ($message, $redirect, $data, $code)
        return jsonResponse(true, $arg1, $arg3, $arg4, $arg2);
    }
}

if (! function_exists('jsonError')) {
    /**
     * Create standardized error JSON response
     *
     * @param string $message
     * @param int $code
     * @param array $data
     * @param string|null $redirect
     * @return \Illuminate\Http\JsonResponse
     */
    function jsonError($message = 'Error', $code = 500, $data = [], $redirect = null)
    {
        return jsonResponse(false, $message, $data, $code, $redirect);
    }
}
