<?php

namespace App\Console\Commands;

use App\Http\Services\PurchaseIndentService;
use App\Http\Services\SalesIndentService;
use App\Models\SalesIndent;
use Exception;
use Illuminate\Console\Command;

class SI_TO_PI extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SI_TO_PI';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron job running with SI_TO_PI. Converting Sales Indent to Purchase Indent';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $allPendingSalesIndent = SalesIndent::where('status', 'PENDING')->get();
            $pendingSalesIndentIds = [];
            foreach ($allPendingSalesIndent as $salesIndent) {
                $pendingSalesIndentIds[] = $salesIndent->id;
            }

            PurchaseIndentService::class->createPurchaseIndent($pendingSalesIndentIds);

        } catch (Exception $e) {
            throw $e;
        }
    }
}
