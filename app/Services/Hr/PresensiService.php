<?php
namespace App\Services\Hr;

use App\Models\Shared\Pegawai;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PresensiService
{
    /**
     * Get presensi settings from cache or database
     */
    public function getPresensiSettings()
    {
        return Cache::remember('presensi_settings', 3600, function () {
            return [
                'office_latitude'  => config('presensi.default_latitude', -6.208763),
                'office_longitude' => config('presensi.default_longitude', 106.845599),
                'office_address'   => config('presensi.default_address', 'Jakarta, Indonesia'),
                'allowed_radius'   => config('presensi.default_radius', 100), // meters
                'is_active'        => true,
            ];
        });
    }

    /**
     * Update presensi settings
     */
    public function updateSettings(array $data): void
    {
        $settings = [
            'office_latitude'  => $data['office_latitude'],
            'office_longitude' => $data['office_longitude'],
            'office_address'   => $data['office_address'],
            'allowed_radius'   => $data['allowed_radius'],
            'is_active'        => $data['is_active'] ?? true,
        ];

        // Clear existing cache first
        Cache::forget('presensi_settings');

        // Store in cache for 1 hour
        Cache::put('presensi_settings', $settings, 3600);

        // Log for debugging
        Log::info('Presensi settings updated', $settings);
    }

    /**
     * Check-in user
     */
    public function checkIn(array $data): array
    {
        $settings = $this->getPresensiSettings();

        if (! $settings['is_active']) {
            throw new \Exception('Presensi tidak aktif');
        }

        $user        = auth()->user();
        $currentTime = Carbon::now();

        // Check if already checked in today
        if ($this->hasCheckedInToday($user)) {
            throw new \Exception('Anda sudah melakukan check-in hari ini');
        }

        // Validate location
        $distance = $this->calculateDistance(
            $data['latitude'],
            $data['longitude'],
            $settings['office_latitude'],
            $settings['office_longitude']
        );

        if ($distance > $settings['allowed_radius']) {
            throw new \Exception("Anda berada di luar radius yang diizinkan. Jarak: " . round($distance, 2) . " meter");
        }

        // Validate face verification
        if (! $data['face_verified']) {
            throw new \Exception('Verifikasi wajah gagal. Silakan coba lagi.');
        }

        // For now, return mock data (without database)
        return [
            'user_id'              => $user->id,
            'check_in_time'        => $currentTime->format('H:i:s'),
            'check_in_date'        => $currentTime->format('Y-m-d'),
            'latitude'             => $data['latitude'],
            'longitude'            => $data['longitude'],
            'address'              => $data['address'] ?? $this->getLocationFromCoordinates($data['latitude'], $data['longitude']),
            'distance_from_office' => round($distance, 2),
            'status'               => 'on_time', // Can be calculated based on shift
            'face_verified'        => $data['face_verified'],
            'photo'                => $data['photo'] ?? null,
        ];
    }

    /**
     * Check-out user
     */
    public function checkOut(array $data): array
    {
        $settings = $this->getPresensiSettings();

        if (! $settings['is_active']) {
            throw new \Exception('Presensi tidak aktif');
        }

        $user        = auth()->user();
        $currentTime = Carbon::now();

        // Check if has checked in today
        if (! $this->hasCheckedInToday($user)) {
            throw new \Exception('Anda belum melakukan check-in hari ini');
        }

        // Check if already checked out today
        if ($this->hasCheckedOutToday($user)) {
            throw new \Exception('Anda sudah melakukan check-out hari ini');
        }

        // Validate location
        $distance = $this->calculateDistance(
            $data['latitude'],
            $data['longitude'],
            $settings['office_latitude'],
            $settings['office_longitude']
        );

        if ($distance > $settings['allowed_radius']) {
            throw new \Exception("Anda berada di luar radius yang diizinkan. Jarak: " . round($distance, 2) . " meter");
        }

        // Validate face verification (optional for check-out)
        if (isset($data['face_verified']) && ! $data['face_verified']) {
            throw new \Exception('Verifikasi wajah gagal. Silakan coba lagi.');
        }

        // For now, return mock data (without database)
        return [
            'user_id'              => $user->id,
            'check_out_time'       => $currentTime->format('H:i:s'),
            'check_out_date'       => $currentTime->format('Y-m-d'),
            'latitude'             => $data['latitude'],
            'longitude'            => $data['longitude'],
            'address'              => $data['address'] ?? $this->getLocationFromCoordinates($data['latitude'], $data['longitude']),
            'distance_from_office' => round($distance, 2),
            'face_verified'        => $data['face_verified'] ?? false,
            'photo'                => $data['photo'] ?? null,
        ];
    }

    /**
     * Get address from coordinates using reverse geocoding
     */
    public function getLocationFromCoordinates(float $lat, float $lng): string
    {
        try {
            // Using Nominatim (OpenStreetMap) for free reverse geocoding
            $response = Http::get("https://nominatim.openstreetmap.org/reverse", [
                'format'         => 'json',
                'lat'            => $lat,
                'lon'            => $lng,
                'zoom'           => 18,
                'addressdetails' => 1,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['display_name'] ?? 'Alamat tidak ditemukan';
            }
        } catch (Exception $e) {
            // Fallback if API fails
        }

        return "Lat: {$lat}, Lng: {$lng}";
    }

    /**
     * Calculate distance between two points in meters
     */
    private function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000; // Earth's radius in meters

        $latDiff = deg2rad($lat2 - $lat1);
        $lngDiff = deg2rad($lng2 - $lng1);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
        sin($lngDiff / 2) * sin($lngDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Check if user has checked in today (mock implementation)
     */
    private function hasCheckedInToday(User $user): bool
    {
        // Mock implementation - in real app, check database
        return Cache::get("checkin_{$user->id}_" . date('Y-m-d'), false);
    }

    /**
     * Check if user has checked out today (mock implementation)
     */
    private function hasCheckedOutToday(User $user): bool
    {
        // Mock implementation - in real app, check database
        return Cache::get("checkout_{$user->id}_" . date('Y-m-d'), false);
    }

    /**
     * Get presensi history (mock implementation)
     */
    public function getPresensiHistory(array $filters): array
    {
        // Mock data for demonstration - in real app this would query the DB
        $data = [
            [
                'date'      => '2026-02-10',
                'check_in'  => '08:15:00',
                'check_out' => '17:30:00',
                'status'    => 'on_time',
                'address'   => 'Jakarta, Indonesia',
            ],
            [
                'date'      => '2026-02-09',
                'check_in'  => '08:05:00',
                'check_out' => '17:45:00',
                'status'    => 'on_time',
                'address'   => 'Jakarta, Indonesia',
            ],
            [
                'date'      => '2026-01-15',
                'check_in'  => '08:30:00',
                'check_out' => '17:00:00',
                'status'    => 'late',
                'address'   => 'Jakarta, Indonesia',
            ],
            [
                'date'      => '2025-12-20',
                'check_in'  => '--:--',
                'check_out' => '--:--',
                'status'    => 'absent',
                'address'   => '-',
            ],
        ];

        // Apply filters
        $filtered = array_filter($data, function ($item) use ($filters) {
            $date = Carbon::parse($item['date']);
            
            // Filter by month
            if (isset($filters['month']) && $filters['month'] !== 'all') {
                if ($date->format('m') !== $filters['month']) return false;
            }
            
            // Filter by year
            if (isset($filters['year']) && $filters['year'] !== 'all') {
                if ($date->format('Y') !== $filters['year']) return false;
            }
            
            // Filter by status
            if (isset($filters['status']) && $filters['status'] !== 'all') {
                if ($item['status'] !== $filters['status']) return false;
            }
            
            return true;
        });

        // Reset array keys
        $filtered = array_values($filtered);

        return [
            'data'     => $filtered,
            'total'    => count($filtered),
            'page'     => 1,
            'per_page' => 10,
        ];
    }

    /**
     * Get employee face data for face matching
     */
    public function getEmployeeFaceData($userId)
    {
        try {
            // Get pegawai data based on user_id
            $pegawai = Pegawai::where('user_id', $userId)->first();

            if (! $pegawai || ! $pegawai->face_encoding) {
                // Return mock face data for testing
                return [
                    0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                    0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                    0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                    0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                    0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                    0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                    0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                    0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                    0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                    0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                    0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                    0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                    0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                    0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                    0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                    0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                ];
            }

            // Return actual face encoding from database
            return json_decode($pegawai->face_encoding, true);

        } catch (Exception $e) {
            \Log::error('Error getting employee face data: ' . $e->getMessage());

            // Return mock data as fallback
            return [
                0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
                0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
            ];
        }
    }
}
