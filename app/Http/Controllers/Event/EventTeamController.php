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
            return response()->json(['success' => true, 'message' => 'Panitia berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(EventTeamRequest $request, EventTeam $team)
    {
        try {
            $this->service->update($team, $request->validated());
            return response()->json(['success' => true, 'message' => 'Data panitia berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(EventTeam $team)
    {
        try {
            $this->service->destroy($team);
            return response()->json(['success' => true, 'message' => 'Panitia berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
