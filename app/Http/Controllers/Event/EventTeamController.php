<?php
namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventTeamRequest;
use App\Models\Event\EventTeam;
use App\Services\Event\EventTeamService;

class EventTeamController extends Controller
{
    public function __construct(protected EventTeamService $eventTeamService)
    {}

    public function store(EventTeamRequest $request)
    {
        try {
            $this->eventTeamService->store($request->validated());
            return jsonSuccess('Panitia berhasil ditambahkan');
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal menambahkan panitia: ' . $e->getMessage());
        }
    }

    public function update(EventTeamRequest $request, EventTeam $team)
    {
        try {
            $this->eventTeamService->update($team, $request->validated());
            return jsonSuccess('Data panitia berhasil diperbarui');
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal memperbarui data panitia: ' . $e->getMessage());
        }
    }

    public function destroy(EventTeam $team)
    {
        try {
            $this->eventTeamService->destroy($team);
            return jsonSuccess('Panitia berhasil dihapus');
        } catch (\Exception $e) {
            logError($e);
            return jsonError('Gagal menghapus panitia: ' . $e->getMessage());
        }
    }
}
