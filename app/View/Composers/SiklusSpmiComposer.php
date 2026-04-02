<?php

namespace App\View\Composers;

use App\Services\Pemutu\PeriodeSpmiService;
use Illuminate\View\View;

class SiklusSpmiComposer
{
    protected $periodeSpmiService;

    public function __construct(PeriodeSpmiService $periodeSpmiService)
    {
        $this->periodeSpmiService = $periodeSpmiService;
    }

    /**
     * Bind data to the view.
     *
     * @return void
     */
    public function compose(View $view)
    {
        try {
            $view->with('globalSiklus', $this->periodeSpmiService->getSiklusData());
        } catch (\Exception $e) {
            // Fallback data if error occurs
            $view->with('globalSiklus', [
                'tahun' => (int) date('Y'),
                'years' => collect([(int) date('Y')]),
                'akademik' => null,
                'non_akademik' => null,
            ]);
        }
    }
}
