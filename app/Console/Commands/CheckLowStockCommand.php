<?php

namespace App\Console\Commands;

use App\Http\Services\ProductService;
use Illuminate\Console\Command;

class CheckLowStockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:low-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Products with Low Stock';

    /**
     * Execute the console command.
     */
    public function handle()
    {

    }
}
