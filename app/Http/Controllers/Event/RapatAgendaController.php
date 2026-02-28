<?php
namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\RapatAgendaRequest;
use App\Models\Event\Rapat;
use App\Models\Event\RapatAgenda;
use App\Services\Event\RapatAgendaService;

class RapatAgendaController extends Controller
{
    public function __construct(
        protected RapatAgendaService $rapatAgendaService
    ) {}

    public function create(Rapat $rapat)
    {
        $agenda = new RapatAgenda(['rapat_id' => $rapat->rapat_id]);
        return view('pages.event.rapat.agenda.create-edit-ajax', compact('rapat', 'agenda'));
    }

    public function store(RapatAgendaRequest $request, Rapat $rapat)
    {
        $data = $request->validated();
        $data['rapat_id'] = $rapat->rapat_id;
        $this->rapatAgendaService->store($data);
        return jsonSuccess('Agenda berhasil ditambahkan');
    }

    public function update(RapatAgendaRequest $request, RapatAgenda $agenda)
    {
        $this->rapatAgendaService->update($agenda, $request->validated());
        return jsonSuccess('Agenda berhasil diperbarui');
    }

    public function destroy(RapatAgenda $agenda)
    {
        $this->rapatAgendaService->destroy($agenda);
        return jsonSuccess('Agenda berhasil dihapus');
    }
}
