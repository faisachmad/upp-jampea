<?php

namespace App\Console\Commands;

use App\Models\Pelabuhan;
use App\Models\TipePelabuhan;
use Illuminate\Console\Command;

class SyncPelabuhanTipe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pelabuhan:sync-tipe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync pelabuhan tipe_pelabuhan_id based on tipe string';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Syncing pelabuhan tipe_pelabuhan_id...');

        $pelabuhans = Pelabuhan::whereNull('tipe_pelabuhan_id')->get();
        $updated = 0;
        $notFound = 0;

        foreach ($pelabuhans as $pelabuhan) {
            $tipe = TipePelabuhan::where('nama', $pelabuhan->tipe)->first();

            if ($tipe) {
                $pelabuhan->tipe_pelabuhan_id = $tipe->id;
                $pelabuhan->save();
                $this->line("✓ Updated {$pelabuhan->nama} -> {$tipe->nama} (ID: {$tipe->id})");
                $updated++;
            } else {
                $this->warn("✗ No tipe found for {$pelabuhan->nama} with tipe '{$pelabuhan->tipe}'");
                $notFound++;
            }
        }

        $this->newLine();
        $this->info("Done! Updated {$updated} pelabuhan records.");

        if ($notFound > 0) {
            $this->warn("{$notFound} pelabuhan records could not be matched.");
        }

        return Command::SUCCESS;
    }
}
