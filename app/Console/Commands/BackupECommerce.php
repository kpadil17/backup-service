<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\DbDumper\Databases\MySql;

class BackupEcommerce extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:ecommerce';

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
            
            if (!Storage::disk('s3_ecommerce')->exists($fileName)) {
                Log::error("$fileName not found");
            } else {
                Log::info("Ecommerce Database Backup Success");
                $object = Storage::disk('s3_ecommerce')->get($fileName);
                Storage::put("backups/ecommerce/$date/ecommerce_dumps.sql.zip", $object);
            }
        } catch (\Throwable $e) {
            Log::error($e);
        }
    }
}
