<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\DbDumper\Databases\MySql;

class BackupMarketplace extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:marketplace';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('memory_limit', -1);

        $date = Carbon::now()->format('Y/m/d');
        $fileName = sprintf('backup_%s.sql.zip', Carbon::now()->format("Ymd"));
        try {
            
            if (!Storage::disk('s3_marketplace')->exists($fileName)) {
                Log::error("$fileName not found");
            } else {
                Log::info("Marketplace Database Backup Success");
                $object = Storage::disk('s3_marketplace')->get($fileName);
                Storage::put("backups/marketplace/$date/marketplace_dumps.sql.zip", $object);
            }
        } catch (\Throwable $e) {
            dd($e->getMessage());
            Log::error($e);
        }
    }
}
