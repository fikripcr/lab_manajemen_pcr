<?php

namespace App\Console\Commands;

// Pemutu module deleted - this command is no longer functional
use Illuminate\Console\Command;

class TestSummaryData extends Command
{
    protected $signature = 'test:summary';

    protected $description = 'Test DokumenSpmiController summaryData (DISABLED - Pemutu module deleted)';

    public function handle()
    {
        $this->error('This command is disabled because the Pemutu module has been deleted.');

        return 1;
    }
}
