<?php
namespace App\View\Composers;

use Illuminate\View\View;
use App\Services\Pemutu\PeriodeSpmiService;

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
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('globalSiklus', $this->periodeSpmiService->getSiklusData());
    }
}
