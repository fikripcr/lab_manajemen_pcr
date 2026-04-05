<?php

namespace App\View\Composers;

// Pemutu module deleted - PeriodeSpmiService no longer exists
use Illuminate\View\View;

class SiklusSpmiComposer
{
    /**
     * Bind data to the view.
     *
     * @return void
     */
    public function compose(View $view)
    {
        // Fallback since Pemutu module was deleted
        $view->with('globalSiklus', [
            'tahun' => (int) date('Y'),
            'years' => collect([(int) date('Y')]),
            'akademik' => null,
            'non_akademik' => null,
        ]);
    }
}
