<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventTeamRequest;
use App\Models\Event\Event;
use App\Models\Event\EventTeam;
use App\Models\Hr\Pegawai;
use App\Services\Event\EventTeamService;

class EventTeamController extends Controller
{
    public function __construct(protected EventTeamService $eventTeamService)
    {}

    /**
     * Show create form for team member
     */
    public function create(Event $event)
    {
        $pageTitle = 'Tambah Anggota Tim';
        $pegawais = Pegawai::where('is_active', 1)
            ->orderBy('nama_pegawai')
            ->get(['pegawai_id', 'nama_pegawai', 'nip', 'jabatan']);
        
        return view('pages.event.teams.create-edit-ajax', compact('pageTitle', 'event', 'pegawais'));
    }

    /**
     * Store new team member
     */
    public function store(EventTeamRequest $request, Event $event)
    {
        $validated = $request->validated();
        $validated['event_id'] = $event->event_id;

        $this->eventTeamService->store($validated);

        return jsonSuccess('Anggota tim berhasil ditambahkan');
    }

    /**
     * Show edit form for team member
     */
    public function edit(Event $event, EventTeam $team)
    {
        $pageTitle = 'Edit Anggota Tim';
        $pegawais = Pegawai::where('is_active', 1)
            ->orderBy('nama_pegawai')
            ->get(['pegawai_id', 'nama_pegawai', 'nip', 'jabatan']);
        
        return view('pages.event.teams.create-edit-ajax', compact('pageTitle', 'event', 'team', 'pegawais'));
    }

    /**
     * Update team member
     */
    public function update(EventTeamRequest $request, Event $event, EventTeam $team)
    {
        $this->eventTeamService->update($team, $request->validated());

        return jsonSuccess('Data anggota tim berhasil diperbarui');
    }

    /**
     * Delete team member
     */
    public function destroy(Event $event, EventTeam $team)
    {
        $this->eventTeamService->destroy($team);

        return jsonSuccess('Anggota tim berhasil dihapus');
    }
}
