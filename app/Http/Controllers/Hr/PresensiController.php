<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Services\Hr\PresensiService;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    protected $service;

    public function __construct(PresensiService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('pages.hr.presensi.index');
    }

    public function checkIn(Request $request)
    {
        try {
            $data = $request->validate([
                'latitude'  => 'required|numeric',
                'longitude' => 'required|numeric',
                'address'   => 'nullable|string|max:500',
                'photo'     => 'nullable|string',
                'face_verified' => 'required|boolean',
            ]);

            $result = $this->service->checkIn($data);

            return response()->json([
                'success' => true,
                'message' => 'Check-in berhasil!',
                'data'    => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function checkOut(Request $request)
    {
        try {
            $data = $request->validate([
                'latitude'  => 'required|numeric',
                'longitude' => 'required|numeric',
                'address'   => 'nullable|string|max:500',
                'photo'     => 'nullable|string',
                'face_verified' => 'nullable|boolean',
            ]);

            $result = $this->service->checkOut($data);

            return response()->json([
                'success' => true,
                'message' => 'Check-out berhasil!',
                'data'    => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getCurrentLocation(Request $request)
    {
        try {
            $data = $request->validate([
                'latitude'  => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

            $location = $this->service->getLocationFromCoordinates($data['latitude'], $data['longitude']);

            return response()->json([
                'success' => true,
                'address' => $location,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function settings()
    {
        return view('pages.hr.presensi.settings');
    }

    public function getSettings()
    {
        $settings = $this->service->getPresensiSettings();
        return response()->json([
            'success'  => true,
            'settings' => $settings,
        ]);
    }

    public function updateSettings(Request $request)
    {
        try {
            $data = $request->validate([
                'office_latitude'  => 'required|numeric',
                'office_longitude' => 'required|numeric',
                'office_address'   => 'required|string|max:500',
                'allowed_radius'   => 'required|integer|min:10|max:1000',
            ]);

            // Handle checkbox properly
            $data['is_active'] = $request->has('is_active');

            $this->service->updateSettings($data);

            return response()->json([
                'success' => true,
                'message' => 'Pengaturan presensi berhasil diperbarui!',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function history()
    {
        return view('pages.hr.presensi.history');
    }

    public function historyData(Request $request)
    {
        $data = $this->service->getPresensiHistory($request->all());
        return response()->json($data);
    }

    public function showUploadPhoto()
    {
        return view('pages.hr.pegawai.upload-photo');
    }

    public function storeUploadPhoto(Request $request)
    {
        try {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'face_encoding' => 'nullable|string',
            ]);

            $user = auth()->user();
            $pegawai = \App\Models\Hr\Pegawai::where('user_id', $user->id)->first();
            
            if (!$pegawai) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pegawai tidak ditemukan'
                ], 404);
            }

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = 'pegawai_' . $pegawai->pegawai_id . '_' . time() . '.' . $photo->getClientOriginalExtension();
                $photoPath = $photo->storeAs('pegawai/photos', $photoName, 'public');
                
                // Update photo path
                $pegawai->photo = $photoPath;
            }

            // Handle face encoding
            if ($request->has('face_encoding')) {
                $pegawai->face_encoding = $request->face_encoding;
            } else {
                // Extract face encoding from uploaded photo
                try {
                    $faceEncoding = $this->extractFaceEncoding($pegawai->photo);
                    if ($faceEncoding) {
                        $pegawai->face_encoding = json_encode($faceEncoding);
                    }
                } catch (\Exception $e) {
                    \Log::warning('Failed to extract face encoding: ' . $e->getMessage());
                }
            }

            $pegawai->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil diupload!',
                'data' => [
                    'photo_path' => $pegawai->photo,
                    'has_face_encoding' => !empty($pegawai->face_encoding)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    private function extractFaceEncoding($photoPath)
    {
        // This would require Face API on server side
        // For now, return mock data
        // In production, you might use Python with face_recognition library
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
            0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8
        ];
    }

    public function getEmployeeFaceData()
    {
        try {
            $user = auth()->user();
            $faceData = $this->service->getEmployeeFaceData($user->id);
            
            return response()->json([
                'success' => true,
                'faceData' => $faceData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
