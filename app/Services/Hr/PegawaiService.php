<?php
namespace App\Services\Hr;

use App\Events\Hr\ApprovalProcessed;
use App\Events\Hr\ApprovalRequested;
use App\Models\Hr\Pegawai;
use App\Models\Hr\RiwayatApproval;
use App\Models\Hr\RiwayatDataDiri;
use App\Models\Hr\RiwayatInpassing;
use App\Models\Hr\RiwayatJabFungsional;
use App\Models\Hr\RiwayatJabStruktural;
use App\Models\Hr\RiwayatPendidikan;
use App\Models\Hr\RiwayatPenugasan;
use App\Models\Hr\RiwayatStatAktifitas;
use App\Models\Hr\RiwayatStatPegawai;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PegawaiService
{
    /**
     * Create a new Pegawai with initial Data Diri history.
     */
    public function createPegawai(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Create Pegawai Header (Skeleton)
            $pegawai = Pegawai::create();

            // 2. Create Initial Approval (Auto-Approved for creation)
            $approval = RiwayatApproval::create([
                'model'      => RiwayatDataDiri::class,
                'status'     => 'Approved',
                'keterangan' => 'Initial Data Creation',
                'pejabat'    => Auth::user()->name ?? 'System',
            ]);

            // 3. Create Riwayat Data Diri
            // Add pegwai_id and approval_id to data
            $data['pegawai_id']                = $pegawai->pegawai_id;
            $data['latest_riwayatapproval_id'] = $approval->riwayatapproval_id;

            $riwayat = RiwayatDataDiri::create($data);

            // 4. Update Approval with model_id
            $approval->update(['model_id' => $riwayat->riwayatdatadiri_id]);

            // 5. Update Pegawai Header to point to this latest record
            $pegawai->update([
                'latest_riwayatdatadiri_id' => $riwayat->riwayatdatadiri_id,
            ]);

            return $pegawai;
        });
    }

    /**
     * Request a change for Data Diri (Creates new history + pending approval).
     */
    public function requestDataDiriChange(Pegawai $pegawai, array $data)
    {
        return DB::transaction(function () use ($pegawai, $data) {
            // 1. Create Pending Approval
            $approval = RiwayatApproval::create([
                'model'      => RiwayatDataDiri::class,
                'status'     => 'Pending',
                'keterangan' => 'Request Data Update',
            ]);

            // 2. Prepare Data (Link to Pegawai, but DO NOT update Pegawai's pointer yet)
            $data['pegawai_id']                = $pegawai->pegawai_id;
            $data['latest_riwayatapproval_id'] = $approval->riwayatapproval_id;
            $data['before_id']                 = $pegawai->latest_riwayatdatadiri_id; // Link to previous version

            // 3. Create New History Record
            $riwayat = RiwayatDataDiri::create($data);

            // 4. Update Approval with model_id
            $approval->update(['model_id' => $riwayat->riwayatdatadiri_id]);

            // 5. Dispatch Event for Notification
            ApprovalRequested::dispatch($approval, $pegawai, Auth::user());

            return $riwayat;
        });
    }

    /**
     * Generic Request for Change (For Single-Value States like Status, Jabatan)
     */
    public function requestChange(Pegawai $pegawai, $modelClass, array $data, $headerColumn = null, $existingModel = null)
    {
        return DB::transaction(function () use ($pegawai, $modelClass, $data, $headerColumn, $existingModel) {
            // 1. Create Pending Approval
            $approval = RiwayatApproval::create([
                'model'      => $modelClass,
                'status'     => 'Pending',
                'keterangan' => $existingModel ? 'Request Update' : 'Request Change',
            ]);

            // 2. Prepare Data
            $data['pegawai_id']                = $pegawai->pegawai_id;
            $data['latest_riwayatapproval_id'] = $approval->riwayatapproval_id; // Will be ignored if column doesn't exist

            // Add before_id logic for models that support it
            if (in_array($modelClass, [
                RiwayatInpassing::class,
                RiwayatStatPegawai::class,
                RiwayatStatAktifitas::class,
                RiwayatJabFungsional::class,
                RiwayatJabStruktural::class,
                RiwayatPendidikan::class,
            ])) {
                $headerColumnMap = [
                    RiwayatInpassing::class     => 'latest_riwayatinpassing_id',
                    RiwayatStatPegawai::class   => 'latest_riwayatstatpegawai_id',
                    RiwayatStatAktifitas::class => 'latest_riwayatstataktifitas_id',
                    RiwayatJabFungsional::class => 'latest_riwayatjabfungsional_id',
                    RiwayatJabStruktural::class => 'latest_riwayatjabstruktural_id',
                    RiwayatPendidikan::class    => 'latest_riwayatpendidikan_id',
                ];

                if (isset($headerColumnMap[$modelClass])) {
                    $currentHeaderColumn = $headerColumnMap[$modelClass];
                    $data['before_id']   = $pegawai->{$currentHeaderColumn};
                }
            }

            // Handle update case - use existing model data as base
            if ($existingModel) {
                $data = array_merge($existingModel->getAttributes(), $data);
                unset($data[$existingModel->getKeyName()]); // Remove primary key
                unset($data['created_at']);                 // Remove timestamps
                unset($data['updated_at']);
            }

            // 3. Create Model
            $model = $modelClass::create($data);

            // 4. Update Approval with model_id
            $approval->update(['model_id' => $model->getKey()]);

            // 5. Dispatch Event for Notification
            ApprovalRequested::dispatch($approval, $pegawai, Auth::user());

            return $model;
        });
    }

    /**
     * Generic Request for Addition (For Multi-Value Lists like Keluarga, Pendidikan)
     */
    public function requestAddition(Pegawai $pegawai, $modelClass, array $data)
    {
        return DB::transaction(function () use ($pegawai, $modelClass, $data) {
            // 1. Create Pending Approval
            $approval = RiwayatApproval::create([
                'model'      => $modelClass,
                'status'     => 'Pending',
                'keterangan' => 'Request New Item',
            ]);

            // 2. Prepare Data
            $data['pegawai_id'] = $pegawai->pegawai_id;

            // Check if model has approval column
            $dummy = new $modelClass;
            if (in_array('latest_riwayatapproval_id', $dummy->getFillable()) || Schema::hasColumn($dummy->getTable(), 'latest_riwayatapproval_id')) {
                $data['latest_riwayatapproval_id'] = $approval->riwayatapproval_id;
            }

            // 3. Create Model
            $model = $modelClass::create($data);

            // 4. Update Approval with model_id
            $approval->update(['model_id' => $model->getKey()]);

            // 5. Dispatch Event for Notification
            ApprovalRequested::dispatch($approval, $pegawai, Auth::user());

            return $model;
        });
    }

    /**
     * Approve a specific request (Approval Record).
     */
    public function approveRequest($approvalId)
    {
        return DB::transaction(function () use ($approvalId) {
            $approval = RiwayatApproval::findOrFail($approvalId);

            if ($approval->status !== 'Pending') {
                throw new Exception("Request is not pending.");
            }

            // Update Status
            $approval->update([
                'status'  => 'Approved',
                'pejabat' => Auth::user()->name ?? 'System',
            ]);

            // Resolve Model and Update Header Pointer if applicable
            $modelClass = $approval->model;
            $model      = $modelClass::findOrFail($approval->model_id);
            $pegawai    = Pegawai::findOrFail($model->pegawai_id);

            // Mapping of Model Class to Pegawai Header Column
            // This acts as a registry for which models affect the Pegawai header status
            $headerMap = [
                RiwayatDataDiri::class      => 'latest_riwayatdatadiri_id',
                RiwayatStatPegawai::class   => 'latest_riwayatstatpegawai_id',
                RiwayatStatAktifitas::class => 'latest_riwayatstataktifitas_id',
                RiwayatJabFungsional::class => 'latest_riwayatjabfungsional_id',
                RiwayatJabStruktural::class => 'latest_riwayatjabstruktural_id', // Nullable/Missing in some schemas, enable if exists
                RiwayatPendidikan::class    => 'latest_riwayatpendidikan_id',    // Usually points to "Highest" education
                RiwayatInpassing::class     => 'latest_riwayatinpassing_id',
            ];

            if (array_key_exists($modelClass, $headerMap)) {
                $col = $headerMap[$modelClass];
                $pegawai->update([$col => $model->getKey()]);
            }

            // Dispatch Event for Notification
            ApprovalProcessed::dispatch($approval, $pegawai, Auth::user(), 'approved');

            return $approval;
        });
    }

    /**
     * Reject a specific request (Approval Record).
     */
    public function rejectRequest($approvalId, $reason = null)
    {
        return DB::transaction(function () use ($approvalId, $reason) {
            $approval = RiwayatApproval::findOrFail($approvalId);

            if ($approval->status !== 'Pending') {
                throw new Exception("Request is not pending.");
            }

            // Update Status
            $approval->update([
                'status'     => 'Rejected',
                'pejabat'    => Auth::user()->name ?? 'System',
                'keterangan' => $reason ?? $approval->keterangan,
            ]);

            // Resolve Model and Pegawai for notification
            $modelClass = $approval->model;
            $model      = $modelClass::findOrFail($approval->model_id);
            $pegawai    = Pegawai::findOrFail($model->pegawai_id);

            // Dispatch Event for Notification
            ApprovalProcessed::dispatch($approval, $pegawai, Auth::user(), 'rejected');

            return $approval;
        });
    }

    /**
     * Delete a Pegawai (soft delete).
     */
    public function delete($pegawaiId)
    {
        return DB::transaction(function () use ($pegawaiId) {
            $pegawai = Pegawai::findOrFail($pegawaiId);
            $pegawai->delete();
            return true;
        });
    }
    /*
     * Penugasan Logic (Direct Update, No Approval Workflow)
     */
    public function addPenugasan(Pegawai $pegawai, array $data)
    {
        return DB::transaction(function () use ($pegawai, $data) {
            $data['pegawai_id']  = $pegawai->pegawai_id;
            $data['status']      = 'approved';
            $data['approved_by'] = Auth::id();
            $data['approved_at'] = now();

            $riwayat = RiwayatPenugasan::create($data);

            // Update latest on pegawai
            $pegawai->update(['latest_riwayatpenugasan_id' => $riwayat->riwayatpenugasan_id]);

            return $riwayat;
        });
    }

    public function updatePenugasan(RiwayatPenugasan $penugasan, array $data)
    {
        return $penugasan->update($data);
    }

    public function deletePenugasan(Pegawai $pegawai, RiwayatPenugasan $penugasan)
    {
        return DB::transaction(function () use ($pegawai, $penugasan) {
            $penugasan->delete();

            // Update latest if deleted was latest
            if ($pegawai->latest_riwayatpenugasan_id == $penugasan->riwayatpenugasan_id) {
                $latest = RiwayatPenugasan::where('pegawai_id', $pegawai->pegawai_id)
                    ->orderByDesc('tgl_mulai')
                    ->first();
                $pegawai->update(['latest_riwayatpenugasan_id' => $latest?->riwayatpenugasan_id]);
            }

            return true;
        });
    }

    public function endPenugasan(RiwayatPenugasan $penugasan, $tglSelesai)
    {
        return $penugasan->update(['tgl_selesai' => $tglSelesai]);
    }

    /**
     * Get filtered query for DataTables.
     */
    public function getFilteredQuery($request)
    {
        $query = Pegawai::query()
            ->with([
                'latestDataDiri.posisi',
                'latestDataDiri.departemen',
                'latestStatusPegawai.statusPegawai',
                'latestInpassing.golonganInpassing',
                'atasanSatu.latestDataDiri', // Need name of atasan
                'atasanDua.latestDataDiri',  // Need name of atasan
            ]);

        // Add filtering if needed
        // if ($request->filled('status_pegawai')) { ... }

        return $query;
    }
}
