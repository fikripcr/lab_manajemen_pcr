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
        $view->with('globalSiklus', $this->periodeSpmiService->getSiklusData());
    }
}
