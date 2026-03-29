<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\PersonilRequest;
use App\Models\Hr\Personil;
use App\Services\Hr\PersonilService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PersonilController extends Controller
{
    public function __construct(protected PersonilService $personilService) {}

    public function index()
    {
        $units = $this->personilService->getUnits();

        return view('pages.hr.personil.index', compact('units'));
    }

    public function data(Request $request)
    {
        $query = $this->personilService->getFilteredQuery($request->all());

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('status_aktif', function ($row) {
                return $row->status_aktif
                    ? '<span class="badge bg-success text-white">Aktif</span>'
                    : '<span class="badge bg-secondary text-white">Non-Aktif</span>';
            })
            ->addColumn('user_info', function ($row) {
                if ($row->user) {
                    $roles = $row->user->roles->pluck('name')->implode(', ');

                    return "<span title=\"{$roles}\">{$row->user->name}</span>";
                }

                return '<span class="text-muted fst-italic">Belum terkoneksi</span>';
            })
            ->addColumn('action', function ($row) {
                return view('components.tabler.datatables-actions', [
                    'editUrl' => route('hr.personil.edit-modal.show', $row->encrypted_personil_id),
                    'editModal' => true,
                    'viewUrl' => route('hr.personil.show', $row->encrypted_personil_id),
                    'deleteUrl' => route('hr.personil.destroy', $row->encrypted_personil_id),
                    'extraActions' => ! $row->user_id ? [
                        [
                            'icon' => 'ti ti-user-plus',
                            'text' => 'Generate Data User',
                            'class' => 'dropdown-item generate-user',
                            'dataUrl' => route('hr.personil.generate-user', $row->encrypted_personil_id),
                        ],
                    ] : [],
                ])->render();
            })
            ->rawColumns(['status_aktif', 'user_info', 'action'])
            ->make(true);
    }

    public function show(Personil $personil)
    {
        if (request()->ajax()) {
            return view('pages.hr.personil.show-ajax', compact('personil'));
        }

        return view('pages.hr.personil.show', compact('personil'));
    }

    public function create()
    {
        $units = $this->personilService->getUnits();
        $personil = new Personil;

        return view('pages.hr.personil.create-edit-ajax', compact('personil', 'units'));
    }

    public function store(PersonilRequest $request)
    {
        $this->personilService->createPersonil($request->validated());

        return jsonSuccess('Data Personil berhasil ditambahkan.', route('hr.personil.index'));
    }

    public function editModal(Personil $personil)
    {
        $units = $this->personilService->getUnits();

        return view('pages.hr.personil.edit-ajax', compact('personil', 'units'));
    }

    public function edit(Personil $personil)
    {
        $units = $this->personilService->getUnits();

        return view('pages.hr.personil.create-edit-ajax', compact('personil', 'units'));
    }

    public function update(PersonilRequest $request, Personil $personil)
    {
        $this->personilService->updatePersonil($personil->personil_id, $request->validated());

        return jsonSuccess('Data Personil berhasil diperbarui.', route('hr.personil.index'));
    }

    public function destroy(Personil $personil)
    {
        $this->personilService->deletePersonil($personil->personil_id);

        return jsonSuccess('Data Personil berhasil dihapus.');
    }

    /**
     * Generate user account for personil without user.
     */
    public function generateUser(Personil $personil)
    {
        if ($personil->user) {
            return jsonError('Personil ini sudah memiliki user.');
        }

        try {
            $result = $this->personilService->generateUserForPersonil($personil);

            return jsonSuccess(
                "User berhasil dibuat untuk {$personil->nama}.<br>Email: {$result['email']}<br>Password: {$result['password']}<br>Role: {$result['role']}",
                route('hr.personil.index')
            );
        } catch (\RuntimeException $e) {
            return jsonError($e->getMessage());
        }
    }
}
