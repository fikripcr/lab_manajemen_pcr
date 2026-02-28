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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PresensiController extends Controller
{
    public function __construct(protected PresensiService $presensiService)
    {}

    public function index()
    {
        return view('pages.hr.presensi.index');
    }

    public function checkIn(PresensiCheckInRequest $request)
    {
        $data = $request->validated();
        $result = $this->presensiService->checkIn($data);

        return jsonSuccess('Check-in berhasil!', null, $result);
    }

    public function checkOut(PresensiCheckOutRequest $request)
    {
        $data = $request->validated();
        $result = $this->presensiService->checkOut($data);

        return jsonSuccess('Check-out berhasil!', null, $result);
    }

    public function getCurrentLocation(PresensiLocationRequest $request)
    {
        $data = $request->validated();
        $location = $this->presensiService->getLocationFromCoordinates($data['latitude'], $data['longitude']);

        return response()->json([
            'success' => true,
            'address' => $location,
        ]);
    }

    public function settings()
    {
        return view('pages.hr.presensi.settings');
    }

    public function getSettings()
    {
        $settings = $this->presensiService->getPresensiSettings();
        return response()->json([
            'success'  => true,
            'settings' => $settings,
        ]);
    }

    public function updateSettings(PresensiUpdateSettingsRequest $request)
    {
        $data = $request->validated();

        // Handle checkbox properly
        $data['is_active'] = $request->has('is_active');

        $this->presensiService->updateSettings($data);

        return jsonSuccess('Pengaturan presensi berhasil diperbarui!');
    }

    public function history()
    {
        return view('pages.hr.presensi.history');
    }

    public function historyData(Request $request)
    {
        $data = $this->presensiService->getPresensiHistory($request->all());
        return response()->json($data);
    }

    public function show($date)
    {
        // Mock data for now since we don't have a specific service method for one date detail
        // In real app, this would query by date and auth user
        $data = [
            'date' => $date,
            'check_in' => '08:15:00',
            'check_out' => '17:30:00',
            'status' => 'on_time',
            'duration' => '9 jam 15 menit',
            'shift' => 'Reguler (08:00 - 17:00)',
            'check_in_location' => 'Jakarta, Indonesia',
            'check_out_location' => 'Jakarta, Indonesia',
            'notes' => '-'
        ];

        return view('pages.hr.presensi.ajax.detail', compact('data'));
    }

    public function showUploadPhoto()
    {
        return view('pages.hr.pegawai.upload-photo');
    }

    public function storeUploadPhoto(PresensiUploadPhotoRequest $request)
    {
        // $request->validated() is sufficient, we can access params directly or via validated()
        $request->validated();

        $user    = auth()->user();
        $pegawai = Pegawai::where('user_id', $user->id)->first();

        if (! $pegawai) {
            return jsonError('Data pegawai tidak ditemukan', 404);
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
            } catch (\Exception $e) {
                Log::warning('Failed to extract face encoding: ' . $e->getMessage());
            }
        }

        $pegawai->save();

        return jsonSuccess('Foto berhasil diupload!', null, [
            'photo_path'        => $pegawai->photo,
            'has_face_encoding' => ! empty($pegawai->face_encoding),
        ]);
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
        $user     = auth()->user();
        $faceData = $this->presensiService->getEmployeeFaceData($user->id);

        return response()->json([
            'success'  => true,
            'faceData' => $faceData,
        ]);
    }
}
