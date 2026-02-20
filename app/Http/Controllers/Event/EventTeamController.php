<?php
namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventTeamRequest;
use App\Models\Event\EventTeam;
use App\Services\Event\EventTeamService;

class EventTeamController extends Controller
{
    public function __construct(
        protected EventTeamService $service
    ) {}

    public function store(EventTeamRequest $request)
    {
        try {
            $this->service->store($request->validated());
            return jsonSuccess('Panitia berhasil ditambahkan');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function update(EventTeamRequest $request, EventTeam $team)
    {
        try {
            $this->service->update($team, $request->validated());
            return jsonSuccess('Data panitia berhasil diperbarui');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    public function destroy(EventTeam $team)
    {
        try {
            $this->service->destroy($team);
            return jsonSuccess('Panitia berhasil dihapus');
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }
}
