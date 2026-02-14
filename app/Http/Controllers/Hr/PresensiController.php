<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\PresensiCheckInRequest;
use App\Http\Requests\Hr\PresensiCheckOutRequest;
use App\Http\Requests\Hr\PresensiLocationRequest;
use App\Http\Requests\Hr\PresensiUpdateSettingsRequest;
use App\Http\Requests\Hr\PresensiUploadPhotoRequest;
use App\Models\Hr\Pegawai;
use App\Services\Hr\PresensiService;
use Exception;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    protected $PresensiService;

    public function __construct(PresensiService $PresensiService)
    {
        $this->PresensiService = $PresensiService;
    }

    public function index()
    {
        return view('pages.hr.presensi.index');
    }

    public function checkIn(PresensiCheckInRequest $request)
    {
        try {
            $data = $request->validated();

            $result = $this->PresensiService->checkIn($data);

            return response()->json([
                'success' => true,
                'message' => 'Check-in berhasil!',
                'data'    => $result,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function checkOut(PresensiCheckOutRequest $request)
    {
        try {
            $data = $request->validated();

            $result = $this->PresensiService->checkOut($data);

            return response()->json([
                'success' => true,
                'message' => 'Check-out berhasil!',
                'data'    => $result,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getCurrentLocation(PresensiLocationRequest $request)
    {
        try {
            $data = $request->validated();

            $location = $this->PresensiService->getLocationFromCoordinates($data['latitude'], $data['longitude']);

            return response()->json([
                'success' => true,
                'address' => $location,
            ]);
        } catch (Exception $e) {
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
        $settings = $this->PresensiService->getPresensiSettings();
        return response()->json([
            'success'  => true,
            'settings' => $settings,
        ]);
    }

    public function updateSettings(PresensiUpdateSettingsRequest $request)
    {
        try {
            $data = $request->validated();

            // Handle checkbox properly
            $data['is_active'] = $request->has('is_active');

            $this->PresensiService->updateSettings($data);

            return response()->json([
                'success' => true,
                'message' => 'Pengaturan presensi berhasil diperbarui!',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (Exception $e) {
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
        $data = $this->PresensiService->getPresensiHistory($request->all());
        return response()->json($data);
    }

    public function showUploadPhoto()
    {
        return view('pages.hr.pegawai.upload-photo');
    }

    public function storeUploadPhoto(PresensiUploadPhotoRequest $request)
    {
        try {
            // $request->validated() is sufficient, we can access params directly or via validated()
            $request->validated();

            $user    = auth()->user();
            $pegawai = Pegawai::where('user_id', $user->id)->first();

            if (! $pegawai) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pegawai tidak ditemukan',
                ], 404);
            }

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $photo     = $request->file('photo');
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
                } catch (Exception $e) {
                    Log::warning('Failed to extract face encoding: ' . $e->getMessage());
                }
            }

            $pegawai->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil diupload!',
                'data'    => [
                    'photo_path'        => $pegawai->photo,
                    'has_face_encoding' => ! empty($pegawai->face_encoding),
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
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
            0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8,
        ];
    }

    public function getEmployeeFaceData()
    {
        try {
            $user     = auth()->user();
            $faceData = $this->PresensiService->getEmployeeFaceData($user->id);

            return response()->json([
                'success'  => true,
                'faceData' => $faceData,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
