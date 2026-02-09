<?php
namespace App\Services\Hr;

use App\Models\Hr\Pegawai;
use App\Models\Hr\RiwayatApproval;
use App\Models\Hr\RiwayatDataDiri;
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
            $pegawai = Pegawai::create([
                'created_by' => Auth::id(),
            ]);

            // 2. Create Initial Approval (Auto-Approved for creation)
            $approval = RiwayatApproval::create([
                'model'      => RiwayatDataDiri::class,
                'status'     => 'Approved',
                'keterangan' => 'Initial Data Creation',
                'pejabat'    => Auth::user()->name ?? 'System',
                'created_by' => Auth::id(),
            ]);

            // 3. Create Riwayat Data Diri
            // Add pegwai_id and approval_id to data
            $data['pegawai_id']                = $pegawai->pegawai_id;
            $data['latest_riwayatapproval_id'] = $approval->riwayatapproval_id;
            $data['created_by']                = Auth::id();

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
                'created_by' => Auth::id(),
            ]);

            // 2. Prepare Data (Link to Pegawai, but DO NOT update Pegawai's pointer yet)
            $data['pegawai_id']                = $pegawai->pegawai_id;
            $data['latest_riwayatapproval_id'] = $approval->riwayatapproval_id;
            $data['before_id']                 = $pegawai->latest_riwayatdatadiri_id; // Link to previous version
            $data['created_by']                = Auth::id();

            // 3. Create New History Record
            $riwayat = RiwayatDataDiri::create($data);

            // 4. Update Approval with model_id
            $approval->update(['model_id' => $riwayat->riwayatdatadiri_id]);

            return $riwayat;
        });
    }

    /**
     * Generic Request for Change (For Single-Value States like Status, Jabatan)
     */
    public function requestChange(Pegawai $pegawai, $modelClass, array $data, $headerColumn = null)
    {
        return DB::transaction(function () use ($pegawai, $modelClass, $data, $headerColumn) {
            // 1. Create Pending Approval
            $approval = RiwayatApproval::create([
                'model'      => $modelClass,
                'status'     => 'Pending',
                'keterangan' => 'Request Change',
                'created_by' => Auth::id(),
            ]);

            // 2. Prepare Data
            $data['pegawai_id']                = $pegawai->pegawai_id;
            $data['created_by']                = Auth::id();
            $data['latest_riwayatapproval_id'] = $approval->riwayatapproval_id; // Will be ignored if column doesn't exist

            // Handle 'before_id' or 'old_id' logic if applicable
            // This requires knowing the column name for the "previous" record on the history table
            // For now, simple insert.

            // 3. Create Model
            $model = $modelClass::create($data);

            // 4. Update Approval with model_id
            $approval->update(['model_id' => $model->getKey()]);

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
                'created_by' => Auth::id(),
            ]);

            // 2. Prepare Data
            $data['pegawai_id'] = $pegawai->pegawai_id;
            $data['created_by'] = Auth::id();

            // Check if model has approval column
            $dummy = new $modelClass;
            if (in_array('latest_riwayatapproval_id', $dummy->getFillable()) || Schema::hasColumn($dummy->getTable(), 'latest_riwayatapproval_id')) {
                $data['latest_riwayatapproval_id'] = $approval->riwayatapproval_id;
            }

            // 3. Create Model
            $model = $modelClass::create($data);

            // 4. Update Approval with model_id
            $approval->update(['model_id' => $model->getKey()]);

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
                throw new \Exception("Request is not pending.");
            }

            // Update Status
            $approval->update([
                'status'     => 'Approved',
                'pejabat'    => Auth::user()->name ?? 'System',
                'updated_by' => Auth::id(),
            ]);

            // Resolve Model and Update Header Pointer if applicable
            $modelClass = $approval->model;
            $model      = $modelClass::findOrFail($approval->model_id);
            $pegawai    = Pegawai::findOrFail($model->pegawai_id);

            // Mapping of Model Class to Pegawai Header Column
            // This acts as a registry for which models affect the Pegawai header status
            $headerMap = [
                \App\Models\Hr\RiwayatDataDiri::class      => 'latest_riwayatdatadiri_id',
                \App\Models\Hr\RiwayatStatPegawai::class   => 'latest_riwayatstatpegawai_id',
                \App\Models\Hr\RiwayatStatAktifitas::class => 'latest_riwayatstataktifitas_id',
                \App\Models\Hr\RiwayatJabFungsional::class => 'latest_riwayatjabfungsional_id',
                \App\Models\Hr\RiwayatJabStruktural::class => 'latest_riwayatjabstruktural_id', // Nullable/Missing in some schemas, enable if exists
                \App\Models\Hr\RiwayatPendidikan::class    => 'latest_riwayatpendidikan_id',    // Usually points to "Highest" education
            ];

            if (array_key_exists($modelClass, $headerMap)) {
                $col = $headerMap[$modelClass];
                $pegawai->update([$col => $model->getKey()]);
            }

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

            $riwayat = \App\Models\Hr\RiwayatPenugasan::create($data);

            // Update latest on pegawai
            $pegawai->update(['latest_riwayatpenugasan_id' => $riwayat->riwayatpenugasan_id]);

            return $riwayat;
        });
    }

    public function updatePenugasan(\App\Models\Hr\RiwayatPenugasan $penugasan, array $data)
    {
        return $penugasan->update($data);
    }

    public function deletePenugasan(Pegawai $pegawai, \App\Models\Hr\RiwayatPenugasan $penugasan)
    {
        return DB::transaction(function () use ($pegawai, $penugasan) {
            $penugasan->delete();

            // Update latest if deleted was latest
            if ($pegawai->latest_riwayatpenugasan_id == $penugasan->riwayatpenugasan_id) {
                $latest = \App\Models\Hr\RiwayatPenugasan::where('pegawai_id', $pegawai->pegawai_id)
                    ->orderByDesc('tgl_mulai')
                    ->first();
                $pegawai->update(['latest_riwayatpenugasan_id' => $latest?->riwayatpenugasan_id]);
            }

            return true;
        });
    }

    public function endPenugasan(\App\Models\Hr\RiwayatPenugasan $penugasan, $tglSelesai)
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
                'atasanSatu.latestDataDiri', // Need name of atasan
                'atasanDua.latestDataDiri',  // Need name of atasan
            ]);

        // Add filtering if needed
        // if ($request->filled('status_pegawai')) { ... }

        return $query;
    }
}
